<?php

namespace App\Livewire;

use App\Models\FinancialStatementCollection;
use App\Models\TrialBalance;
use Livewire\Component;

class Home extends Component
{
    public $user;
    public $trialBalances;
    public $financialStatements;

    public function mount($user){
        $this->user = $user;

        $this->trialBalances = TrialBalance::orderBy('created_at', 'desc')->take(6)->get();

        $this->financialStatements = FinancialStatementCollection::orderBy('created_at', 'desc')->take(6)->get();
    }

    public function bentoRedirect(string $location, string $payload){
        return $this->redirect("/$location/$payload", true);
    }
    public function render()
    {
        return view('livewire.home');
    }
}
