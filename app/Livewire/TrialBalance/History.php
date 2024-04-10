<?php

namespace App\Livewire\TrialBalance;

use Livewire\Component;

class History extends Component
{
    public $trial_balance_data;
    public function mount($data){
        $this->trial_balance_data = $data;
    }
    public function render()
    {
        return view('livewire.trial-balance.history', ["numHistory" => $this->trial_balance_data->count()]);
    }
}
