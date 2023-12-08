<?php

namespace App\Livewire\FinancialReporting;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Reactive;

class SuggestionSearch extends Component
{
    public $searchInput;
    #[Reactive]
    public $interimPeriod; // TODO: remove
    public $suggestions = [];
    public $selectedInput;

    public function setSelectedInput(int $suggestionIndex){
        $this->selectedInput = $this->suggestions[$suggestionIndex];
        $this->searchInput = $this->selectedInput->tb_name;

        $this->dispatch('setTbID', tbID: $this->selectedInput->tb_id);
    }

    public function render()
    {
        if($this->searchInput && $this->interimPeriod){
            $this->suggestions = DB::table("trial_balances")
                                    ->select("tb_id", "tb_name", "interim_period", "date")
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
