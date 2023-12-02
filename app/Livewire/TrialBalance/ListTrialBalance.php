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

    public function refreshFilters(){
        $this->reset(['filterPeriod', 'filterQuarter', 'filterStatus']);
    }

    public function create(){
        return $this->redirect('/trial-balances/add', navigate: true);
    }

    public function updatePage(){
        // 
    }
    
    public function render()
    {
        $query = DB::table('trial_balances')->select('tb_id','report_name','date', 'interim_period', 'quarter', 'created_at', 'updated_at', 'report_status');

        // we can change the role in the future
        $defaultReportStatus = auth()->user()->role === 'accounting' ? 'Draft' : 'For Approval';

        if($this->filterPeriod || $this->filterStatus){
            if($this->filterPeriod === 'Quarterly' && $this->filterQuarter){
                $query->where('interim_period', '=', $this->filterPeriod)
                      ->where('quarter', '=', $this->filterQuarter);
            } else {
                $query->where('interim_period', '=', $this->filterPeriod);
            }
        }

        $res = $query->where('report_status', '=', $this->filterStatus ?? $defaultReportStatus)->paginate($this->rows);

        $this->hasMorePages = $res->hasMorePages();
        
        return view('livewire.trial-balance.list-trial-balance', [
           "trial_balances" => $res
        ]);
    }
}
