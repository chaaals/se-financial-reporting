<?php

namespace App\Livewire\FinancialStatementCollection;

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
    public $date;
    public $interimPeriod;
    public $quarter;
    public $tbID;
    public $tbName;
    public $fscID;
    public $fsTypes = ["SFPO", "SFPE", "SCF"];

    protected $listeners = ["setTrialBalance" => "setTrialBalance"];

    protected $rules = [
        "fsName" => "nullable|max:255",
        "date" => "required|date",
        "interimPeriod" => "required|in:Quarterly,Annual",
        "quarter" => "nullable",
        "tbID" => "required",
    ];

    protected $messages = [
        "interimPeriod.required" => "You need to select an interim period.",
        "tbID.required" => "You need to select a Trial Balance reference."
    ];

    public function mount()
    {
        // default values so user does not need to interact with the form and just save
        $this->date = date('Y-m-d');
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
            "date" => $this->date,
            "interim_period" => $this->interimPeriod,
            "tb_id" => $this->tbID,
        ]);
        $this->fscID = $fs_col->collection_id;
        $this->addFS();
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
        DB::table('financial_statements')->insert([
            "fs_type" => "SFPO",
            "fs_data" => $sfpoData,
            "collection_id" => $this->fscID,
            "template_name" => "sfpo",
        ]);
        DB::table('fs_account_totals')->insert([
            "fs_id" => $this->fscID,
            "totals_data" => $sfpoTotals,
        ]);

        [$sfpeData, $sfpeTotals] = $this->getData($tbData, "sfpe_tb");
        DB::table('financial_statements')->insert([
            "fs_type" => "SFPE",
            "fs_data" => $sfpeData,
            "collection_id" => $this->fscID,
            "template_name" => "SFPE",
        ]);
        DB::table('fs_account_totals')->insert([
            "fs_id" => $this->fscID,
            "totals_data" => $sfpeTotals,
        ]);

        [$scfData, $scfTotals] = $this->getData($tbData, "scf_tb");
        DB::table('financial_statements')->insert([
            "fs_type" => "SCF",
            "fs_data" => $scfData,
            "collection_id" => $this->fscID,
            "template_name" => "SCF",
        ]);
        DB::table('fs_account_totals')->insert([
            "fs_id" => $this->fscID,
            "totals_data" => $scfTotals,
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

        $totalsConfig = DB::select('SELECT totals FROM report_templates WHERE template_name = ?', [$fsType . "_totals"]);
        if ($totalsConfig) {
            $totalsConfig = $totalsConfig[0];
        }
        $totalsArray = json_decode($totalsConfig->totals, true);
        $totalsResults = [];
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
        if ($this->date && $this->interimPeriod === 'Quarterly') {
            $fr_month = date('m', strtotime($this->date));
            $this->rules['quarter'] = 'required|in:Q1,Q2,Q3,Q4';
            $quarter = ceil($fr_month / 3);
            $this->quarter = "Q$quarter";
        }

        if ($this->interimPeriod === "Annual") {
            $this->quarter = null;
        }

        return view('livewire.financial-statement-collection.add-financial-statement-collection');
    }
}
