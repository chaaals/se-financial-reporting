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

    public $filterPeriod;
    public $filterQuarter;
    public $filterStatus;

    public $sortIndices = [
        0 => "report_name",
        1 => "date",
        2 => "interim_period",
        3 => "quarter",
        4 => "created_at",
        5 => "updated_at",
        6 => "report_status",
    ];

    public $sortBy;
    
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

    public function refreshFilters(){
        $this->reset(['filterPeriod', 'filterQuarter', 'filterStatus', 'sortBy']);
    }

    public function create(){
        return $this->redirect('/trial-balances/add', navigate: true);
    }

    public function updatePage(){
        $this->setPage(1);
    }
    
    public function render()
    {
        $query = DB::table('trial_balances')->select('tb_id','report_name','date', 'interim_period', 'quarter', 'created_at', 'updated_at', 'report_status');

        // we can change the role in the future
        $defaultReportStatus = auth()->user()->role === 'accounting' ? 'Draft' : 'For Approval';

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

            $query->where('report_status', '=', $this->filterStatus ?? $defaultReportStatus);
        }

        if($this->sortBy){
            $query->orderBy($this->sortBy, 'desc');
        }

        $res = $query->paginate($this->rows);

        $this->hasMorePages = $res->hasMorePages();
        
        return view('livewire.trial-balance.list-trial-balance', [
           "trial_balances" => $res
        ]);
    }
}
