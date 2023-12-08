<?php

namespace App\Livewire\FinancialReporting;

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
            $this->suggestions = DB::table("trial_balances as tb")
                                    ->select(
                                        "tb.tb_id as tb_id",
                                        "tb.tb_name as tb_name",
                                        "tb.interim_period as interim_period",
                                        "tb.date as date")
                                    ->whereNotExists(function($query){
                                        $query->select(DB::raw(1))
                                              ->from("financial_statement_collections as fsc")
                                              ->whereColumn('tb.tb_id', 'fsc.tb_id');
                                    })
                                    ->where("interim_period", "=", $this->interimPeriod)
                                    ->where("tb_name", "like", "%$this->searchInput%")
                                    ->limit(10)
                                    ->get();
        }

        return view('livewire.financial-reporting.suggestion-search',[
            "suggestions" => $this->suggestions
        ]);
    }
}
