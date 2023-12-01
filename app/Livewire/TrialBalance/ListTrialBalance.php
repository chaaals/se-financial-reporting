<?php

namespace App\Livewire\TrialBalance;

use App\Models\TrialBalance;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListTrialBalance extends Component
{
    use WithPagination;

    public $hasMorePages;
    public $confirming = null;
    public $rows = 10;

    public function confirmDelete($tbID)
    {
        $this->confirming = $tbID;
    }

    public function deleteTrialBalance($tbID)
    {
        // delete by ID
        TrialBalance::find($tbID)->delete();
        // refresh
        // TODO: Change to DB query
        $this->trial_balances = TrialBalance::all();
        $this->reset('confirming');
    }

    public function previous(){
        $this->previousPage();
    }

    public function next(){
        if($this->hasMorePages){
            $this->nextPage();
        }
    }

    public function updatePage(){
        // 
    }
    
    public function render()
    {
        $trial_balances = DB::table('trial_balances')
                                    ->select('tb_id','report_name','report_status','tb_type','interim_period','date')
                                    ->paginate($this->rows);

        $this->hasMorePages = $trial_balances->hasMorePages();
        
        return view('livewire.trial-balance.list-trial-balance', [
           "trial_balances" => $trial_balances
        ]);
    }
}
