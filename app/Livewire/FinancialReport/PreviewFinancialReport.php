<?php

namespace App\Livewire\FinancialReport;

use App\Models\FinancialReport;
use App\Models\TrialBalance;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class PreviewFinancialReport extends Component
{
    public $financial_report;
    public $trial_balances;
    public $confirming = null;
    public $editMode = false;
    public $editedReportName;
    public $editedTBID;
    public $editedApproved;
    public $editedReportStatus;
    protected $rules = [
        'editedReportName' => 'nullable|max:255',
        'editedApproved' => 'required|boolean',
        'editedReportStatus' => 'required|in:Draft,For Approval,Approved',
        'editedTBID' => 'required',
    ];

    public function mount(){
        $report_id = Route::current()->parameter("report_id");

        // TODO change to db queries
        $this->financial_report = FinancialReport::find($report_id);
        $this->trial_balances = TrialBalance::all();

        $this->editedReportName = $this->financial_report->report_name;
        $this->editedApproved = $this->financial_report->approved;
        $this->editedReportStatus = $this->financial_report->report_status;
        $this->editedTBID = $this->financial_report->tb_id;
    }

    public function confirmDelete($report_id)
    {
        $this->confirming = $report_id;
    }

    public function deleteFinancialReport($report_id)
    {
        // delete by ID
        FinancialReport::find($report_id)->delete();
        $this->reset('confirming');
        $this->redirect("/financial-reports");
    }

    public function toggleEditMode()
    {
        $this->editMode = !$this->editMode;
    }

    public function updateFinancialReport()
    {
        $this->validate();
        // check if the report is already approved but changed to not approved
        if ($this->financial_report->approved) {
            if (!$this->editedApproved) {
                $this->editedReportStatus = 'For Approval';
            }
        }

        // if not approved in the first place but changed to not approved
        if ($this->editedApproved) {
            $this->editedReportStatus = 'Approved';
        }
        

        // update fields
        $this->financial_report->report_name = $this->editedReportName;
        $this->financial_report->approved = $this->editedApproved;
        $this->financial_report->report_status = $this->editedReportStatus;
        $this->financial_report->tb_id = $this->editedTBID;
        $this->financial_report->save();

        // exit edit mode
        $this->editMode = false;
    }

    public function render()
    {
        return view('livewire.financial-report.preview-financial-report');
    }
}