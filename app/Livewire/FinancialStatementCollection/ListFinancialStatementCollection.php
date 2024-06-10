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
    public $fsCollection;
    public $confirming = null;

    public $hasMorePages;
    public $rows = 10;
    public $filterPeriod;
    public $filterQuarter;
    public $filterStatus;
    public $filterReportFlag = "Active";
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
            "options" => ["Draft", "Change Requested", "For Approval", "Approved"]
        ],
        "Flag" => [
            "model" => "filterReportFlag",
            "options" => ["Active", "Archived"]
        ]
    ];

    public $sortBy;
    public $sortIndices = [
        0 => "collection_name",
        1 => "interim_period",
        2 => "quarter",
        3 => "fsc_year",
        4 => "created_at",
        5 => "updated_at",
        6 => "collection_status",
    ];
    public $searchInput;

    public function mount()
    {
        // TODO: Change to DB query builder and paginate
        // $this->filterStatus = auth()->user()->role === 'accounting' ? 'Draft' : 'For Approval';
        $this->filterStatus = auth()->user()->role_id === intval(env('ACCOUNTING_ROLE_ID', '9')) ? 'Draft' : 'For Approval';
    }

    public function getFSinit($fscID)
    {
        return FinancialStatement::where('collection_id', $fscID)->get();
    }

    public function preview(string $fscId)
    {
        return $this->redirect("/financial-statements/$fscId", navigate: true);
    }

    public function previous()
    {
        $this->previousPage();
    }

    public function next()
    {
        if ($this->hasMorePages) {
            $this->nextPage();
        }
    }

    public function search()
    {
    }

    public function sort(int $sortIndex)
    {
        $this->sortBy = $this->sortIndices[$sortIndex];
    }

    public function updatePage()
    {
        $this->setPage(1);
    }

    public function refreshFilters()
    {
        // $this->filterStatus = auth()->user()->role === 'accounting' ? 'Draft' : 'For Approval';
        $this->filterStatus = auth()->user()->role_id === intval(env('ACCOUNTING_ROLE_ID', '9')) ? 'Draft' : 'For Approval';
        $this->reset(['filterPeriod', 'filterQuarter', 'sortBy']);
    }

    public function create()
    {
        return $this->redirect('/financial-statements/add', navigate: true);
    }

    public function archive()
    {
        if (count($this->fsCollections) === 0 || in_array($this->fsCollection->collection_status, ['Draft', 'For Approval', 'Change Requested'])) {
            return;
        }

        $collection_id = $this->fsCollection->collection_id;
        $collection_name = $this->fsCollection->collection_name;
        FinancialStatementCollection::where('collection_id', '=', $collection_id)->delete();

        // $user = auth()->user()->first_name . " " . auth()->user()->last_name;
        $user = auth()->user()->role_id == intval(env('ACCOUNTING_ROLE_ID', '9')) ? 'Mara Calinao' : 'Andrea Malunes';
        activity()->withProperties(['user' => $user, 'role' => auth()->user()->role_id])->log("Archived $collection_name");

        $this->setFSCollection();
        session()->now('success', "$collection_name has been archived.");
    }

    public function setFSCollection($itemIndex = null)
    {
        if ($itemIndex === null) {
            $this->fsCollection = null;
            return;
        }

        $this->fsCollection = $this->fsCollections[$itemIndex];
    }

    public function render()
    {
        $query = null;
        if (in_array($this->filterReportFlag, ['Active'])) {
            $query = FinancialStatementCollection::select('collection_id', 'collection_name', 'fsc_year', 'interim_period', 'quarter', 'created_at', 'updated_at', 'collection_status', 'deleted_at');
        } else {
            $query = FinancialStatementCollection::onlyTrashed()->select('collection_id', 'collection_name', 'fsc_year', 'interim_period', 'quarter', 'created_at', 'updated_at', 'collection_status', 'deleted_at');
        }


        $isCorrectPeriodFilter = in_array($this->filterPeriod, ['Monthly', 'Annual', 'Quarterly']);
        $isCorrectStatusFilter = in_array($this->filterStatus, ['Draft', 'For Approval', 'Approved']);

        if ($isCorrectPeriodFilter || $isCorrectStatusFilter) {
            if ($this->filterPeriod === 'Quarterly' && $this->filterQuarter) {
                $query->where('interim_period', '=', $this->filterPeriod)
                    ->where('quarter', '=', $this->filterQuarter);
            }

            if ($isCorrectPeriodFilter) {
                $query->where('interim_period', '=', $this->filterPeriod);
            }
        }

        if ($this->searchInput) {
            $searchInput = "%$this->searchInput%";
            $query->where('collection_name', 'like', $searchInput);
            $this->searchInput = null;
        }

        if ($this->sortBy) {
            // refactor suggestion: modify enums to follow alphabetical order para madali sorting
            if (in_array($this->sortBy, ['interim_period', 'quarter', 'collection_status'])) {
                $query->orderBy($this->sortBy, 'desc');
            } else {
                $query->orderBy($this->sortBy, 'asc');
            }
        }

        $res = null;
        if (in_array($this->filterReportFlag, ['Active'])) {
            $res = $query->where('collection_status', '=', $this->filterStatus)->paginate($this->rows);
        } else {
            $res = $query->paginate($this->rows);
        }
        $this->fsCollections = $res->items();

        $this->hasMorePages = $res->hasMorePages();

        return view('livewire.financial-statement-collection.list-financial-statement-collection', [
            "fs_collection" => $res
        ]);
    }
}
