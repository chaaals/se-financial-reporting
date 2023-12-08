<?php

namespace App\Livewire\FinancialStatementCollection;

use App\Models\FinancialStatementCollection;
use App\Models\FinancialStatement;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListFinancialStatementCollection extends Component
{
    use WithPagination;
    public $fsCollections;
    public $confirming = null;

    public $hasMorePages;
    public $rows = 10;
    public $filterPeriod;
    public $filterQuarter;
    public $filterStatus;
    public $filterOptions = [
        "Period" => [
            "model" => "filterPeriod",
            "options" => ["Monthly", "Quarterly", "Annual"]
        ],
        "Quarter" => [
            "model" => "filterQuarter",
            "options" => ["Q1", "Q2", "Q3", "Q4"]
        ],
        "Status" => [
            "model" => "filterStatus",
            "options" => ["Draft", "For Approval", "Approved"]
        ],
    ];

    public $sortBy;
    public $sortIndices = [
        0 => "collection_name",
        1 => "date",
        2 => "interim_period",
        3 => "quarter",
        4 => "created_at",
        5 => "updated_at",
        6 => "collection_status",
    ];
    public $searchInput;

    public function mount(){
        // TODO: Change to DB query builder and paginate
        // $this->fsCollections = FinancialStatementCollection::all();
        // $this->financialStatements = FinancialStatement::all();
        $this->filterStatus = auth()->user()->role === 'accounting' ? 'Draft' : 'For Approval';
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

    public function preview(string $fscId){
        return $this->redirect("/financial-statements/$fscId", navigate: true);
    }

    public function previous(){
        $this->previousPage();
    }

    public function next(){
        if($this->hasMorePages){
            $this->nextPage();
        }
    }

    public function search(){
        
    }

    public function sort(int $sortIndex){
        $this->sortBy = $this->sortIndices[$sortIndex];
    }

    public function updatePage(){
        $this->setPage(1);
    }

    public function refreshFilters(){
        $this->filterStatus = auth()->user()->role === 'accounting' ? 'Draft' : 'For Approval';
        $this->reset(['filterPeriod', 'filterQuarter', 'sortBy']);
    }

    public function create(){
        return $this->redirect('/financial-statements/add', navigate: true);
    }

    public function render()
    {
        $query = DB::table('financial_statement_collections')->select('collection_id','collection_name','date', 'interim_period', 'quarter', 'created_at', 'updated_at', 'collection_status');

        $isCorrectPeriodFilter = in_array($this->filterPeriod, ['Monthly', 'Annual', 'Quarterly']);
        $isCorrectStatusFilter = in_array($this->filterStatus, ['Draft', 'For Approval', 'Approved']);

        if($isCorrectPeriodFilter || $isCorrectStatusFilter){
            if($this->filterPeriod === 'Quarterly' && $this->filterQuarter){
                $query->where('interim_period', '=', $this->filterPeriod)
                      ->where('quarter', '=', $this->filterQuarter);
            }
            
            if($isCorrectPeriodFilter){
                $query->where('interim_period', '=', $this->filterPeriod);
            }
        }

        if($this->searchInput){
            $searchInput = "%$this->searchInput%";
            $query->where('collection_name', 'like', $searchInput);
            $this->searchInput = null;
        }

        if($this->sortBy){
            // refactor suggestion: modify enums to follow alphabetical order para madali sorting
            if(in_array($this->sortBy, ['interim_period', 'quarter', 'collection_status'])){
                $query->orderBy($this->sortBy, 'desc');
            } else {
                $query->orderBy($this->sortBy, 'asc');
            }
        }

        $res = $query->where('collection_status', '=', $this->filterStatus)->paginate($this->rows);

        $this->hasMorePages = $res->hasMorePages();

        return view('livewire.financial-statement-collection.list-financial-statement-collection', [
            "fs_collection" => $res
        ]);
    }
}
