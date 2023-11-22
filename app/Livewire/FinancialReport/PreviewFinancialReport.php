<?php

namespace App\Livewire\FinancialReport;

use App\Models\FinancialReport;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class PreviewFinancialReport extends Component
{
    public $financial_report;
    public $confirming = null;
    public $editMode = false;
    public $editedReportName;
    public $editedApproved;
    public $editedReportStatus;

    public function mount(){
        $report_id = Route::current()->parameter("report_id");
        $this->financial_report = FinancialReport::find($report_id);
        $this->editedReportName = $this->financial_report->report_name;
        $this->editedApproved = $this->financial_report->approved;
        $this->editedReportStatus = $this->financial_report->report_status;
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
        $this->financial_report->save();

        // exit edit mode
        $this->editMode = false;
    }

    public function render()
    {
        return view('livewire.financial-report.preview-financial-report');
    }
}