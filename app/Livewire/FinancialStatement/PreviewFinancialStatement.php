<?php

namespace App\Livewire\FinancialStatement;

use App\Models\FinancialStatement;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Url;
use Livewire\Component;

class PreviewFinancialStatement extends Component
{
    public FinancialStatement $financialStatement;

    public function mount(){
        $statementId = Route::current()->parameter("statement_id");
        $query = FinancialStatement::where("statement_id", $statementId)->get();

        foreach($query as $fs){
            $this->financialStatement = $fs;
        }
    }
    public function render()
    {
        return view('livewire.financial-statement.preview-financial-statement');
    }
}
