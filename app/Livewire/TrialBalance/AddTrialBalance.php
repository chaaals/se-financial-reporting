<?php

namespace App\Livewire\TrialBalance;

use App\Imports\TrialBalanceImport;
use App\Models\TrialBalance;
use App\Models\TrialBalanceHistory;
use App\Models\TrialBalanceTotals;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AddTrialBalance extends Component
{
    use WithFileUploads;

    public $tbName;
    public $tbType;
    public $tbData;
    public $tbDataTotals;
    public $tbDate;
    public $interimPeriod;
    public $quarter;

    public $tbList;
    public $updateExistingTbId;

    public $source;
    public $importedSpreadsheet;

    public $spreadsheet = [];
    public $preview = [];
    protected $rules = [
        "tbName" => "nullable|max:255",
        "tbDate" => "required|date",
        "tbType" => "nullable|in:pre,post",
        "importedSpreadsheet" => "required|file|mimes:xlsx,xls,ods",
        'interimPeriod' => 'required|in:Monthly,Quarterly,Annual',
        'quarter' => 'nullable',
    ];

    public function mount()
    {
        // default values so user does not need to interact with the form and just save
        $this->tbDate = date('Y-m-d');

        $formattedDate = date('M d, Y', strtotime($this->tbDate));
        $this->tbName = "Trial Balance Report as of $formattedDate";
        $this->listTb();
    }

    public function listTb()
    {
        $res = DB::select("SELECT tb_name, tb_id FROM trial_balances");
        $this->tbList = array_map(function ($item) {
            return ['name' => $item->tb_name, 'id' => $item->tb_id];
        }, $res);
    }

    public function add()
    {
        $fr_month = date('m', strtotime($this->tbDate));

        if ($this->interimPeriod === 'Quarterly') {
            $this->rules['quarter'] = 'required|in:Q1,Q2,Q3,Q4';
            $quarter = ceil($fr_month / 3);
            $this->quarter = "Q$quarter";
            $this->tbName = "Q$quarter Trial Balance " . date('Y');
        } else {
            $this->quarter = null;
            if ($this->interimPeriod === "Annual") {
                $this->tbName = "Annual Trial Balance " . date('Y');
                $this->tbType = "pre";
            } else {
                $this->tbName = "Trial Balance " . date('Y-m');
            }
        }

        $this->validate();
        if ($this->importedSpreadsheet && $this->tbData) {
            $tb = TrialBalance::create([
                "tb_name" => $this->tbName,
                "tb_type" => $this->tbType ?? null,
                "tb_status" => 'Draft',
                "interim_period" => $this->interimPeriod,
                "quarter" => $this->quarter,
                "approved" => false,
                "tb_date" => $this->tbDate,
                "template_name" => 'tb_pre',
                "debit_grand_totals" => $this->tbDataTotals['GRAND TOTALS']['debit'],
                "credit_grand_totals" => $this->tbDataTotals['GRAND TOTALS']['credit'],
            ]);

            TrialBalanceHistory::create([
                "tb_id" => $tb->tb_id,
                "tb_data" => $this->tbData,
                "date" => $this->tbDate
            ]);
            $this->reset();

            TrialBalanceTotals::create([
                "tb_id" => $tb->tb_id,
                "totals_data" => $this->tbDataTotals,
            ]);
        }

        session()->flash("success", "Trial Balance has been added.");
        $this->redirect('/trial-balances', navigate: true);
    }

    public function update()
    {
        $res = DB::select("SELECT tb_date, interim_period FROM trial_balances WHERE tb_id = $this->updateExistingTbId");
        $this->tbDate = $res[0]->tb_date;
        $this->interimPeriod = $res[0]->interim_period;
        $this->validate;
        if ($this->importedSpreadsheet && $this->tbData) {
            TrialBalanceHistory::create([
                "tb_id" => $this->updateExistingTbId,
                "tb_data" => $this->tbData,
                "date" => $this->tbDate,
            ]);
            $this->reset();
        }
        // update the trial balance's credit and debit grand totals
        $tb = TrialBalance::find($this->updateExistingTbId);
        $tb->debit_grand_totals = $this->tbDataTotals['GRAND TOTALS']['debit'];
        $tb->credit_grand_totals = $this->tbDataTotals['GRAND TOTALS']['credit'];
        $tb->save();
        // update trial balance's totals
        $tbTotals = TrialBalanceTotals::find($this->updateExistingTbId);
        $tbTotals->totals_data = $this->tbDataTotals;
        $tbTotals->save();

        session()->flash("success", "Trial Balance has been updated.");
        $this->redirect('/trial-balances', navigate: true);
    }

    private function getTBData()
    {
        $spreadsheet = IOFactory::load($this->importedSpreadsheet->getRealPath());

        $tbImportConfig = DB::select("SELECT template FROM report_templates WHERE template_name = 'tb_pre'");
        $jsonConfig = array_column($tbImportConfig, 'template')[0];
        $jsonConfig = json_decode($jsonConfig, true);
        $tbData = $jsonConfig;
        foreach ($jsonConfig as $accountCode => $row) {
            $debit = $spreadsheet->getActiveSheet()->getCell("F" . $row)->getCalculatedValue();
            $credit = $spreadsheet->getActiveSheet()->getCell("H" . $row)->getCalculatedValue();
            $tbData[$accountCode] = [
                "debit" => $debit,
                "credit" => $credit
            ];
        }

        $tbTotalsConfig = DB::select("SELECT template FROM report_templates WHERE template_name = 'tb_pre_totals'");
        $totalsConfig = array_column($tbTotalsConfig, 'template')[0];
        $totalsConfig = json_decode($totalsConfig, true);
        $tbDataTotals = $totalsConfig;
        foreach ($jsonConfig as $title => $row) {
            $debit = $spreadsheet->getActiveSheet()->getCell("F" . $row)->getCalculatedValue();
            $credit = $spreadsheet->getActiveSheet()->getCell("H" . $row)->getCalculatedValue();
            $tbData[$title] = [
                "debit" => $debit,
                "credit" => $credit
            ];
        }

        $this->tbData = json_encode($tbData);
        $this->tbDataTotals = json_encode($tbDataTotals);
        session()->now("success", "Import successful!");
    }

    public function resetImport()
    {
        if ($this->tbData && $this->importedSpreadsheet) {
            $this->reset(['tbData', 'importedSpreadsheet']);
        }
    }
    public function cancel()
    {
        return $this->redirect('/trial-balances', navigate: true);
    }

    public function render()
    {
        if ($this->importedSpreadsheet) {
            $this->getTBData();
        }

        return view('livewire.trial-balance.add-trial-balance');
    }
}
