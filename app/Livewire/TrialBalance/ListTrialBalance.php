<?php

namespace App\Livewire\TrialBalance;

use App\Models\TrialBalance;
use Livewire\Component;

class ListTrialBalance extends Component
{
    public $trial_balances = [];

    public function mount() {
        // TODO: Change to DB query
        $this->trial_balances = TrialBalance::all();
    }
    public function render()
    {
        return view('livewire.trial-balance.list-trial-balance');
    }
}
