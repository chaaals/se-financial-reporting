<?php

namespace App\Livewire\FinancialStatement;

use App\Models\FinancialStatement;
use Livewire\Attributes\Url;
use Livewire\Component;

class ListFinancialStatement extends Component
{
    public $financialStatements;
    public $confirming = null;
    #[Url]
    public $type = '';

    public function mount(){
        // TODO: Change to DB query builder and paginate
        $this->financialStatements = $this->type ? FinancialStatement::where('statement_type', $this->type)->get() : FinancialStatement::all();
    }

    public function confirmDelete($fsID)
    {
        $this->confirming = $fsID;
    }

    public function deleteFinancialStatement($fsID)
    {
        // delete by ID
        FinancialStatement::find($fsID)->delete();
        // refresh
        // TODO: Change to DB query
        $this->financialStatements = FinancialStatement::all();
        $this->reset('confirming');
    }

    public function render()
    {
        return view('livewire.financial-statement.list-financial-statement');
    }
}
