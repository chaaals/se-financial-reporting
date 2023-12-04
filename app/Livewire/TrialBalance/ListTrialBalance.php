<?php

namespace App\Livewire\TrialBalance;

use App\Models\TrialBalance;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListTrialBalance extends Component
{
    use WithPagination;

    public $hasMorePages;
    public $confirming = null;
    public $rows = 10;

    
    public $sortBy;
    public $sortIndices = [
        0 => "tb_name",
        1 => "date",
        2 => "interim_period",
        3 => "quarter",
        4 => "created_at",
        5 => "updated_at",
        6 => "tb_status",
    ];
    
    public $searchInput;

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

    public function mount(){
        $this->filterStatus = auth()->user()->role === 'accounting' ? 'Draft' : 'For Approval';
    }

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

    public function sort(int $sortIndex){
        $this->sortBy = $this->sortIndices[$sortIndex];
    }

    public function search(){
        
    }

    public function preview(string $tbId){
        return $this->redirect("/trial-balances/$tbId", navigate: true);
    }

    public function refreshFilters(){
        $this->filterStatus = auth()->user()->role === 'accounting' ? 'Draft' : 'For Approval';
        $this->reset(['filterPeriod', 'filterQuarter', 'sortBy']);
    }

    public function create(){
        return $this->redirect('/trial-balances/add', navigate: true);
    }

    public function updatePage(){
        $this->setPage(1);
    }
    
    public function render()
    {
        $query = DB::table('trial_balances')->select('tb_id','tb_name','date', 'interim_period', 'quarter', 'created_at', 'updated_at', 'tb_status');

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
            $query->where('tb_name', 'like', $searchInput);
            $this->searchInput = null;
        }

        if($this->sortBy){
            // refactor suggestion: modify enums to follow alphabetical order para madali sorting
            if(in_array($this->sortBy, ['interim_period', 'quarter', 'tb_status'])){
                $query->orderBy($this->sortBy, 'desc');
            } else {
                $query->orderBy($this->sortBy, 'asc');
            }
        }

        $res = $query->where('tb_status', '=', $this->filterStatus)->paginate($this->rows);

        $this->hasMorePages = $res->hasMorePages();
        
        return view('livewire.trial-balance.list-trial-balance', [
           "trial_balances" => $res
        ]);
    }
}
