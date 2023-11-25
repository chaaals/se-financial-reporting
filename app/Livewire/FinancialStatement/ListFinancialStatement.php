<?php

namespace App\Livewire\FinancialStatement;

use App\Models\FinancialStatement;
use Livewire\Attributes\Url;
use Livewire\Component;

class ListFinancialStatement extends Component
{
    public $financialStatements;

    #[Url]
    public $type = '';

    public function mount(){
        // TODO: Change to DB query builder and paginate
        $this->financialStatements = $this->type ? FinancialStatement::where('statement_type', $this->type)->get() : FinancialStatement::all();
    }
    public function render()
    {
        return view('livewire.financial-statement.list-financial-statement');
    }
}
