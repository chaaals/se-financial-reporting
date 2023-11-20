<?php

namespace App\Livewire\TrialBalance;

use App\Models\TrialBalance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class PreviewTrialBalance extends Component
{
    public $trial_balance;

    public function mount(){
        $tb_id = Route::current()->parameter("tb_id");
        $query = TrialBalance::where('tb_id', $tb_id)->get();

        foreach($query as $tb){
            $this->trial_balance= $tb;
        }
    }
    public function render()
    {
        return view('livewire.trial-balance.preview-trial-balance');
    }
}
