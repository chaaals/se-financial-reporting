<?php

namespace App\Livewire\FinancialStatement;

// use App\Exports\FinancialStatementExport;
use App\Models\FinancialStatement;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class PreviewFinancialStatement extends Component
{
    public FinancialStatement $financialStatement;
    public $confirming = null;
    public $editMode = false;
    public $editedReportName;
    public $editedFSType;
    public $editedDate;
    public $editedInterimPeriod;
    public $editedQuarter;
    public $editedApproved;
    public $editedReportStatus;

    protected $rules = [
        'editedReportName' => 'nullable|max:255',
        'editedFSType' => 'required|in:SFPE,SFPO,SCF',
        'editedDate' => 'required|date',
        'editedInterimPeriod' => 'required|in:Quarterly,Annual',
        'editedQuarter' => 'nullable|in:Q1,Q2,Q3,Q4',
        'editedReportStatus' => 'required|in:Draft,For Approval,Approved',
        'editedApproved' => 'required|boolean',
    ];

    public function mount(){
        $tb_id = Route::current()->parameter("statement_id");
        $query = FinancialStatement::where('statement_id', $tb_id)->get();

        foreach($query as $tb){
            $this->financialStatement= $tb;
        }

        // default values
        $this->editedReportName = $this->financialStatement->report_name;
        $this->editedDate = $this->financialStatement->date;
        $this->editedInterimPeriod = $this->financialStatement->interim_period;
        $this->editedQuarter = $this->financialStatement->quarter;
        $this->editedApproved = $this->financialStatement->approved;
        $this->editedReportStatus = $this->financialStatement->report_status;
        $this->editedFSType = $this->financialStatement->fs_type;
        
    }

    // public function export() {
    //     $export = new FinancialStatementExport(json_decode($this->financialStatement->tb_data));

    //     return Excel::download($export, 'TB_REPORT.xlsx');
    // }

    public function confirmDelete($tbId)
    {
        $this->confirming = $tbId;
    }

    public function deleteFinancialStatement($tbId)
    {
        // delete by ID
        FinancialStatement::find($tbId)->delete();
        $this->reset('confirming');
        $this->redirect("/financial-statements");
    }

    public function toggleEditMode()
    {
        $this->editMode = !$this->editMode;
    }

    public function updateFinancialStatement()
    {
        $this->validate();
        // check if the report is already approved but changed to not approved
        if ($this->financialStatement->approved) {
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
            $fs_month = date('m', strtotime($this->editedDate));
            $quarter = ceil($fs_month / 3);
            $this->editedQuarter = "Q$quarter";
        }
        
        // update fields
        $this->financialStatement->report_name = $this->editedReportName;
        $this->financialStatement->fs_type = $this->editedFSType;
        $this->financialStatement->date = $this->editedDate;
        $this->financialStatement->interim_period = $this->editedInterimPeriod;
        $this->financialStatement->quarter = $this->editedQuarter;
        $this->financialStatement->approved = $this->editedApproved;
        $this->financialStatement->report_status = $this->editedReportStatus;
        $this->financialStatement->template_name = strtolower($this->editedFSType);
        $this->financialStatement->save();

        // exit edit mode
        $this->editMode = false;
    }

    public function render()
    {
        return view('livewire.financial-statement.preview-financial-statement');
    }
}
