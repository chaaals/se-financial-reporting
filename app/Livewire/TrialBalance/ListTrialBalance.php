<?php

namespace App\Livewire\TrialBalance;

use App\Models\TrialBalance;
use Livewire\Component;

class ListTrialBalance extends Component
{
    public $trial_balances = [];
    public $confirming = null;

    public function mount() {
        // TODO: Change to DB query
        $this->trial_balances = TrialBalance::all();
    }

    public function confirmDelete($tbId)
    {
        $this->confirming = $tbId;
    }

    public function deleteTrialBalance($tbId)
    {
        // delete by ID ($tbId) and refresh
        TrialBalance::find($tbId)->delete();
        // TODO: Change to DB query
        $this->trial_balances = TrialBalance::all();
        $this->confirming = null;
    }
    
    public function render()
    {
        return view('livewire.trial-balance.list-trial-balance');
    }
}
