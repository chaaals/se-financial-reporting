<?php

namespace App\Livewire\TrialBalance;

use App\Exports\TrialBalanceExport;
use App\Models\TrialBalance;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class PreviewTrialBalance extends Component
{
    public TrialBalance $trial_balance;
    public $reportType = "tb";
    public $confirming = null;
    public $editMode = false;
    public $editedReportName;
    public $editedDate;
    public $editedInterimPeriod;
    public $editedQuarter;
    public $editedApproved;
    public $editedReportStatus;

    protected $rules = [
        'editedReportName' => 'nullable|max:255',
        'editedDate' => 'required|date',
        'editedInterimPeriod' => 'required|in:Quarterly,Annual',
        'editedQuarter' => 'nullable|in:Q1,Q2,Q3,Q4',
        'editedReportStatus' => 'required|in:Draft,For Approval,Approved',
        'editedApproved' => 'required|boolean',
    ];

    public function mount(){
        $tb_id = Route::current()->parameter("tb_id");
        $query = TrialBalance::where('tb_id', $tb_id)->get();

        foreach($query as $tb){
            $this->trial_balance= $tb;
        }

        // default values
        $this->editedReportName = $this->trial_balance->report_name;
        $this->editedDate = $this->trial_balance->date;
        $this->editedInterimPeriod = $this->trial_balance->interim_period;
        $this->editedQuarter = $this->trial_balance->quarter;
        $this->editedApproved = $this->trial_balance->approved;
        $this->editedReportStatus = $this->trial_balance->report_status;
    }

    public function export() {
        $export = new TrialBalanceExport(json_decode($this->trial_balance->tb_data));

        return Excel::download($export, 'TB_REPORT.xlsx');
    }

    public function confirmDelete($tbId)
    {
        $this->confirming = $tbId;
    }

    public function deleteTrialBalance($tbId)
    {
        // delete by ID
        TrialBalance::find($tbId)->delete();
        $this->reset('confirming');
        $this->redirect("/trial-balances");
    }

    public function toggleEditMode()
    {
        $this->editMode = !$this->editMode;
    }

    public function updateTrialBalance()
    {
        $this->validate();
        // check if the report is already approved but changed to not approved
        if ($this->trial_balance->approved) {
            if (!$this->editedApproved) {
                $this->editedReportStatus = 'For Approval';
            }
        }

        // if not approved in the first place but changed to not approved
        if ($this->editedApproved) {
            $this->editedReportStatus = 'Approved';
        }

        if ($this->editedInterimPeriod === "Annual") {
            $this->editedQuarter = null;
        } else {
            $tb_month = date('m', strtotime($this->editedDate));
            $quarter = ceil($tb_month / 3);
            $this->editedQuarter = "Q$quarter";
        }
        
        // update fields
        $this->trial_balance->report_name = $this->editedReportName;
        $this->trial_balance->date = $this->editedDate;
        $this->trial_balance->interim_period = $this->editedInterimPeriod;
        $this->trial_balance->quarter = $this->editedQuarter;
        $this->trial_balance->approved = $this->editedApproved;
        $this->trial_balance->report_status = $this->editedReportStatus;
        $this->trial_balance->save();

        // exit edit mode
        $this->editMode = false;
    }

    public function render()
    {
        return view('livewire.trial-balance.preview-trial-balance',
            ["statusColor" => strtolower(join("", explode(" ",$this->trial_balance->tb_status)))]
        );
    }
}
