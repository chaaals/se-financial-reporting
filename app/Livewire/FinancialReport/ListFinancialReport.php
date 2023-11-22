<?php

namespace App\Livewire\FinancialReport;

use App\Models\FinancialReport;
use App\Models\TrialBalance;
use Livewire\Component;

class ListFinancialReport extends Component
{
    public $financial_reports = [];
    public $trial_balances = [];
    public $confirming = null;
    public $editMode = false;
    public $editedReportID;
    public $editedReportName;
    public $editedApproved;
    public $editedReportStatus;
    public $editedTBID;

    public function mount() {
        // TODO: Change to DB queries
        $this->financial_reports = FinancialReport::all();
        $this->trial_balances = TrialBalance::all();    
    }

    public function confirmDelete($report_id)
    {
        $this->confirming = $report_id;
    }

    public function deleteFinancialReport($report_id)
    {
        // delete by ID
        FinancialReport::find($report_id)->delete();
        
        // refresh
        // TODO: Change to DB query
        $this->financial_reports = FinancialReport::all();
        $this->reset('confirming');
    }

    public function toggleEditMode($report_id)
    {
        $this->editMode = true;
        $this->editedReportID = $report_id;

        $report = FinancialReport::find($report_id);
        $this->editedReportName = $report->report_name;
        $this->editedApproved = $report->approved;
        $this->editedReportStatus = $report->report_status;
        $this->editedTBID = $report->tb_id;
    }

    public function updateFinancialReport()
    {
        $report = FinancialReport::find($this->editedReportID);

        // check if the report is already approved but changed to not approved
        if ($report->approved) {
            if (!$this->editedApproved) {
                $this->editedReportStatus = 'For Approval';
            }
        }

        // if not approved in the first place but changed to not approved
        if ($this->editedApproved) {
            $this->editedReportStatus = 'Approved';
        }

        // Update financial report fields
        $report->report_name = $this->editedReportName;
        $report->approved = $this->editedApproved;
        $report->report_status = $this->editedReportStatus;
        $report->tb_id = $this->editedTBID;
        $report->save();
        
        $this->editMode = false;
        
        // refresh only the edited report in the list view
        $index = array_search($this->editedReportID, array_column($this->financial_reports->toArray(), 'report_id'));
        $this->financial_reports[$index] = $report;
    }
    
    public function render()
    {
        return view('livewire.financial-report.list-financial-report');
    }
}
