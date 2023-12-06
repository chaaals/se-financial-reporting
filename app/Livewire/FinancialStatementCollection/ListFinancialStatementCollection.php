<?php

namespace App\Livewire\FinancialStatementCollection;

use App\Models\FinancialStatementCollection;
use App\Models\FinancialStatement;
use Livewire\Attributes\Url;
use Livewire\Component;

class ListFinancialStatementCollection extends Component
{
    public $fsCollections;
    public $confirming = null;
    public $financialStatements;

    public function mount(){
        // TODO: Change to DB query builder and paginate
        $this->fsCollections = FinancialStatementCollection::all();
        $this->financialStatements = FinancialStatement::all();
    }

    public function getFSinit($fscID) {
        return FinancialStatement::where('collection_id', $fscID)->get();
    }

    public function confirmDelete($fscID)
    {
        $this->confirming = $fscID;
    }

    public function deleteFinancialStatementCollection($fscID)
    {
        // delete by ID
        FinancialStatementCollection::find($fscID)->delete();
        // refresh
        // TODO: Change to DB query
        $this->fsCollections = FinancialStatementCollection::all();
        $this->reset('confirming');
    }

    public function render()
    {
        return view('livewire.financial-statement-collection.list-financial-statement-collection');
    }
}
