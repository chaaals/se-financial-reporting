<?php

namespace App\Livewire\TrialBalance;

use App\Models\TrialBalance;
use App\Models\FinancialReport;
use Livewire\Component;

class ListTrialBalance extends Component
{
    public $trial_balances = [];
    public $confirming = null;

    public function mount() {
        // TODO: Change to DB query
        $this->trial_balances = TrialBalance::all();
        $this->financial_reports = FinancialReport::all();
    }

    public function confirmDelete($report_id)
    {
        $this->confirming = $report_id;
    }

    public function deleteTrialBalance($report_id)
    {
        // delete by ID
        FinancialReport::find($report_id)->delete();
        // refresh
        // TODO: Change to DB query
        $this->trial_balances = TrialBalance::all();
        $this->reset('confirming');
    }
    
    public function render()
    {
        return view('livewire.trial-balance.list-trial-balance');
    }
}
