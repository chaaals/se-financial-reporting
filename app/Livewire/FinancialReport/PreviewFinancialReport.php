<?php

namespace App\Livewire\FinancialReport;

use App\Models\FinancialReport;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class PreviewFinancialReport extends Component
{
    public $financial_report;
    public $confirming = null;

    public function mount(){
        $report_id = Route::current()->parameter("report_id");
        $this->financial_report = FinancialReport::find($report_id);
    }

    public function confirmDelete($report_id)
    {
        $this->confirming = $report_id;
    }

    public function deleteFinancialReport($report_id)
    {
        // Delete financial report by ID
        FinancialReport::find($report_id)->delete();
        
        // Redirect after deletion
        $this->reset('confirming');
        $this->redirect("/financial-reports");
    }

    public function render()
    {
        return view('livewire.financial-report.preview-financial-report');
    }
}
