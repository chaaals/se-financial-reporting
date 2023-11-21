<?php

namespace App\Livewire\TrialBalance;

use App\Models\TrialBalance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class PreviewTrialBalance extends Component
{
    public $trial_balance;
    public $confirming = null;

    public function mount(){
        $tb_id = Route::current()->parameter("tb_id");
        $query = TrialBalance::where('tb_id', $tb_id)->get();

        foreach($query as $tb){
            $this->trial_balance= $tb;
        }
    }

    public function confirmDelete($tbId)
    {
        $this->confirming = $tbId;
    }

    public function deleteTrialBalance($tbId)
    {
        // delete by ID
        TrialBalance::find($tbId)->delete();
        $this->reset('confirming');
        $this->redirect("/trial-balances");
    }

    public function render()
    {
        return view('livewire.trial-balance.preview-trial-balance');
    }
}
