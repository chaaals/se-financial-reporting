<?php

namespace App\Livewire\FinancialReporting;

use App\Models\TrialBalance;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Reactive;

class SuggestionSearch extends Component
{
    public $searchInput;
    #[Reactive]
    public $interimPeriod;
    public $suggestions = [];
    public $selectedInput;

    public function setSelectedInput(int $suggestionIndex){
        $this->selectedInput = $this->suggestions[$suggestionIndex];
        $this->searchInput = $this->selectedInput->tb_name;

        $this->dispatch('setTrialBalance',
            tbID: $this->selectedInput->tb_id,
            tbName:$this->selectedInput->tb_name);
    }

    public function render()
    {
        if($this->searchInput && $this->interimPeriod){
            // $this->suggestions = DB::table("trial_balances as tb")
            //                         ->select(
            //                             "tb.tb_id as tb_id",
            //                             "tb.tb_name as tb_name",
            //                             "tb.interim_period as interim_period",
            //                             "tb.tb_status as tb_status",
            //                             "tb.debit_grand_totals as debit_grand_totals",
            //                             "tb.credit_grand_totals as credit_grand_totals",
            //                             "tb.tb_date as date")
            //                         ->whereNotExists(function($query){
            //                             $query->select(DB::raw(1))
            //                                   ->from("financial_statement_collections as fsc")
            //                                   ->whereColumn('tb.tb_id', 'fsc.tb_id');
            //                         })
            //                         ->where("interim_period", "=", $this->interimPeriod)
            //                         ->where("approved", "=", true)
            //                         ->where("tb_name", "like", "%$this->searchInput%")
            //                         ->limit(10)
            //                         ->get();
            
            $this->suggestions = TrialBalance::select(
                'tb_id',
                'tb_name',
                'interim_period',
                'tb_status',
                'debit_grand_totals',
                'credit_grand_totals',
                'tb_date as date'
            )
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('financial_statement_collections')
                      ->whereColumn('trial_balances.tb_id', 'financial_statement_collections.tb_id');
            })
            ->where('interim_period', $this->interimPeriod)
            ->where('approved', true)
            ->where('tb_name', 'like', '%' . $this->searchInput . '%')
            ->limit(10)
            ->get();
            $this->suggestions = $this->suggestions->filter(function ($item){ return ($item->debit_grand_totals + $item->credit_grand_totals) == 0; })->values();
        }

        return view('livewire.financial-reporting.suggestion-search',[
            "suggestions" => $this->suggestions
        ]);
    }
}
