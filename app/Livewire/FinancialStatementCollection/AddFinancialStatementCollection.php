<?php

namespace App\Livewire\FinancialStatementCollection;

use App\Models\FinancialStatement;
use App\Models\TrialBalance;
use App\Models\FinancialStatementCollection;
use App\Models\ReportTemplate;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\IOFactory;
use DB;
use Storage;

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
    public $spreadsheet = [];
    public $preview = [];
    public $trialBalances = [];
    public $fscID;
    public $fsTypes = ["SFPO", "SFPE", "SCF"];

    public $confirming = false;

    protected $rules = [
        "fsName" => "nullable|max:255",
        "date" => "required|date",
        "interimPeriod" => "required|in:Quarterly,Annual",
        "quarter" => "nullable",
        "tbID" => "required",
    ];

    public function mount()
    {
        $results = DB::table('trial_balances')->get();
        foreach ($results as $result) {
            $trialBalance = new TrialBalance();
            $trialBalance->tb_id = $result->tb_id;
            $trialBalance->tb_name = $result->tb_name;
            $this->trialBalances[] = $trialBalance;
        }

        // default values so user does not need to interact with the form and just save
        $this->date = date('Y-m-d');
        if (count($this->trialBalances) > 0) {
            $this->tbID = $this->trialBalances[0]->tb_id;
            $this->tbName = $this->trialBalances[0]->tb_name;
        }
    }

    public function add(){
        $fr_month = date('m', strtotime($this->date));
        if ($this->interimPeriod === 'Quarterly') {
            $this->rules['quarter'] = 'required|in:Q1,Q2,Q3,Q4';
            $quarter = ceil($fr_month / 3);
            $this->quarter = "Q$quarter";
            $this->fsName = !$this->fsName ? "Q$quarter Financial Statements " . date('Y') : $this->fsName;
        } else {
            $this->quarter = null;
            $this->fsName = !$this->fsName ? "Annual Financial Statements " . date('Y') : $this->fsName;
        }
    
        $this->validate();
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
        $this->confirming = true;

        // reassign values for preview when confirming
        $this->tbID = $fs_col->tb_id;
        $this->tbName = DB::select("SELECT tb_name FROM trial_balances WHERE tb_id = ?", [$this->tbID])[0]->tb_name;
    }
    
    public function addFS()
    {
        $tbData = DB::select('SELECT tb_data from trial_balances WHERE tb_id = ?', [$this->tbID])[0];
        $sfpoData = $this->getData($tbData, "sfpo_tb");
        DB::table('financial_statements')->insert([
            "fs_type" => "SFPO",
            "fs_data" => $sfpoData,
            "collection_id" => $this->fscID,
            "template_name" => "sfpo",
        ]);
        $sfpeData = $this->getData($tbData, "sfpe_tb");
        DB::table('financial_statements')->insert([
            "fs_type" => "SFPE",
            "fs_data" => $sfpeData,
            "collection_id" => $this->fscID,
            "template_name" => "SFPE",
        ]);
        $scfData = $this->getData($tbData, "scf_tb");
        DB::table('financial_statements')->insert([
            "fs_type" => "SCF",
            "fs_data" => $scfData,
            "collection_id" => $this->fscID,
            "template_name" => "SCF",
        ]);
        $this->reset();
        $this->redirect('/financial-statements');
    }

    public function cancelAddFS()
    {
        $this->reset();
        return redirect('/financial-statements');
    }

    public function getData($tbData, $fsType) {
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
        return json_encode($results);
    }

    public function cancel(){
        return $this->redirect('/financial-statements', navigate: true);
    }

    public function render()
    {
        if($this->date && $this->interimPeriod === 'Quarterly') {
            $fr_month = date('m', strtotime($this->date));
            $this->rules['quarter'] = 'required|in:Q1,Q2,Q3,Q4';
            $quarter = ceil($fr_month / 3);
            $this->quarter = "Q$quarter";
        }

        if($this->interimPeriod === "Annual"){
            $this->quarter = null;
        }

        return view('livewire.financial-statement-collection.add-financial-statement-collection');
    }
}
