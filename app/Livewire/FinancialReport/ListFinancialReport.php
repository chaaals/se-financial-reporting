<?php

namespace App\Livewire\FinancialReport;

use App\Models\FinancialReport;
use Livewire\Component;

class ListFinancialReport extends Component
{
    public $financial_reports = [];
    public $confirming = null;

    public function mount() {
        // TODO: Change to DB query
        $this->financial_reports = FinancialReport::all();
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

    public function render()
    {
        return view('livewire.financial-report.list-financial-report');
    }
}
