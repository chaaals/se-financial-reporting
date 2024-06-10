<?php

namespace App\Livewire\FinancialStatementCollection;

use App\Models\FinancialStatement;
use App\Models\FinancialStatementCollection;
use App\Models\TrialBalance;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;


class AddFinancialStatementCollection extends Component
{
    use WithFileUploads;

    public $fsName;
    public $fsData;
    // public $date;
    public $year;
    public $interimPeriod;
    public $quarter;
    public $tbID;
    public $tbName;
    public $fscID;
    public $fsTypes = ["SFPO", "SFPE", "SCF", "SCNAE", "SCBAA"];
    public $trialBalances = [
        'Trial Balance' => [
            'model' => 'tbID',
            'options' => []
        ]
    ];

    protected $listeners = ["setTrialBalance" => "setTrialBalance"];

    protected $rules = [
        "fsName" => "nullable|max:255",
        // "date" => "required|date",
        "interimPeriod" => "required|in:Quarterly,Annual",
        "quarter" => "nullable",
        "year" => "required|integer",
        "tbID" => "required",
    ];

    protected $messages = [
        "interimPeriod.required" => "You need to select an interim period.",
        "tbID.required" => "You need to select a Trial Balance reference."
    ];

    public function mount()
    {
        // default values so user does not need to interact with the form and just save
        $this->year = date('Y');
    }

    public function add()
    {
        $this->validate();

        if ($this->interimPeriod === 'Quarterly') {
            $this->fsName = !$this->fsName ? "$this->quarter Financial Statements " . date('Y') : $this->fsName;
        } else {
            $this->fsName = !$this->fsName ? "Annual Financial Statements " . date('Y') : $this->fsName;
        }

        $fs_col = FinancialStatementCollection::create([
            "collection_name" => $this->fsName,
            "collection_status" => 'Draft',
            "quarter" => $this->quarter,
            "approved" => false,
            "fsc_year" => $this->year,
            "interim_period" => $this->interimPeriod,
            "tb_id" => $this->tbID,
        ]);
        $this->fscID = $fs_col->collection_id;
        $this->addFS();

        $user = auth()->user()->first_name . " " . auth()->user()->last_name;
        activity()->withProperties(['user' => $user, 'role' => auth()->user()->role])->log("Add $fs_col->collection_name");
    }

    public function addFS()
    {
        if (!$this->tbID) {
            return;
        }

        $tb = TrialBalance::with('latestTbData')->where('tb_id', $this->tbID)->get()[0];
        $tbData = $tb->getRelation('latestTbData');

        // $tbData = DB::select('SELECT tb_data from trial_balances WHERE tb_id = ?', [$this->tbID])[0];
        [$sfpoData, $sfpoTotals] = $this->getData($tbData, "sfpo_tb");
        FinancialStatement::create([
            "fs_type" => "SFPO",
            "fs_data" => $sfpoData,
            "totals_data" => $sfpoTotals,
            "collection_id" => $this->fscID,
            "template_name" => "sfpo",
        ]);

        [$sfpeData, $sfpeTotals] = $this->getData($tbData, "sfpe_tb");
        FinancialStatement::create([
            "fs_type" => "SFPE",
            "fs_data" => $sfpeData,
            "totals_data" => $sfpeTotals,
            "collection_id" => $this->fscID,
            "template_name" => "sfpe",
        ]);

        [$scfData, $scfTotals] = $this->getData($tbData, "scf_tb");
        FinancialStatement::create([
            "fs_type" => "SCF",
            "fs_data" => $scfData,
            "totals_data" => $scfTotals,
            "collection_id" => $this->fscID,
            "template_name" => "scf",
        ]);

        if ($this->interimPeriod == 'Annual') {
            [$scnaeData, $scnaeTotals] = $this->getData($tbData, "scnae_tb");
            FinancialStatement::create([
                "fs_type" => "SCNAE",
                "fs_data" => $scnaeData,
                "totals_data" => $scnaeTotals,
                "collection_id" => $this->fscID,
                "template_name" => "scnae",
            ]);
        }

        [$scbaaData, $scbaaTotals] = $this->getData($tbData, "scbaa_tb");
        FinancialStatement::create([
            "fs_type" => "SCBAA",
            "fs_data" => $scbaaData,
            "totals_data" => $scbaaTotals,
            "collection_id" => $this->fscID,
            "template_name" => "scbaa",
        ]);

        $this->reset();
        session()->flash("success", "Financial Statement Collection has been created.");
        $this->redirect('/financial-statements', navigate: true);
    }

    public function getData($tbData, $fsType)
    {
        $fsConfig = DB::select('SELECT template FROM report_templates WHERE template_name = ?', [$fsType]);
        if ($fsConfig) {
            $fsConfig = $fsConfig[0];
        }

        $configArray = json_decode($fsConfig->template, true);
        $dataArray = json_decode($tbData->tb_data, true);
        $results = [];

        foreach ($configArray as $rowNumber => $accountNumbers) {
            $sum = 0;

            foreach ($accountNumbers as $accountCode) {
                if (is_array($accountCode)) {
                    $accountSum = 0;
                    foreach ($accountCode as $code) {
                        if (array_key_exists($code, $dataArray)) {
                            $debit = $dataArray[$code]['debit'];
                            $credit = $dataArray[$code]['credit'];
                            $difference = ($debit !== null ? $debit : 0) - ($credit !== null ? $credit : 0);
                            $accountSum += $difference;
                        }
                    }
                    $sum += $accountSum;
                } else {
                    if (array_key_exists($accountCode, $dataArray)) {
                        $debit = $dataArray[$accountCode]['debit'];
                        $credit = $dataArray[$accountCode]['credit'];
                        $difference = ($debit !== null ? $debit : 0) - ($credit !== null ? $credit : 0);
                        $sum += $difference;
                    }
                }
            }
            $results[$rowNumber] = $sum;
        }

        $totalsConfig = DB::select('SELECT template FROM report_templates WHERE template_name = ?', [$fsType . "_totals"]);
        if ($totalsConfig) {
            $totalsConfig = $totalsConfig[0];
        }

        $totalsArray = json_decode($totalsConfig->template, true);
        $totalsResults = [];
        if (!$totalsArray) {
            dd($totalsConfig->template);
        }
        foreach ($totalsArray as $title => $rows) {
            $sum = 0;
            foreach ($rows as $row) {
                if (array_key_exists($row, $results)) {
                    $sum += $results[$row];
                }
            }
            $totalsResults[$title] = $sum;
        }
        return [json_encode($results), json_encode($totalsResults)];
    }

    public function setTrialBalance(string $tbID, string $tbName)
    {
        $this->tbID = $tbID;
        $this->tbName = $tbName;
    }

    public function cancel()
    {
        return $this->redirect('/financial-statements', navigate: true);
    }

    public function render()
    {
        if ($this->interimPeriod === 'Quarterly' && $this->quarter) {
            $this->trialBalances['Trial Balance']['options'] = TrialBalance::select(['tb_id', 'tb_name', DB::raw('debit_grand_totals - credit_grand_totals as balance_difference')])
                ->where('interim_period', 'Quarterly')
                ->where('quarter', $this->quarter)
                ->where('approved', true)
                ->whereNotIn('trial_balances.tb_id', function ($query) {
                    $query->select('tb_id')->from('financial_statement_collections');
                })
                ->having('balance_difference', '=', 0)
                ->get()->toArray();
        } else if ($this->interimPeriod === 'Quarterly' && count($this->trialBalances['Trial Balance']['options']) > 0) {
            $this->trialBalances['Trial Balance']['options'] = [];
        }

        if ($this->interimPeriod === "Annual") {
            $this->quarter = null;
            $this->trialBalances['Trial Balance']['options'] = TrialBalance::select(['tb_id', 'tb_name', DB::raw('debit_grand_totals - credit_grand_totals as balance_difference')])
                ->where('interim_period', 'Annual')
                ->where('tb_year', $this->year)
                ->where('approved', true)
                ->whereNotIn('trial_balances.tb_id', function ($query) {
                    $query->select('tb_id')->from('financial_statement_collections');
                })
                ->having('balance_difference', '=', 0)
                ->get()->toArray();
        }


        return view('livewire.financial-statement-collection.add-financial-statement-collection');
    }
}
