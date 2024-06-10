<?php

namespace App\Livewire\TrialBalance;

use App\Imports\TrialBalanceImport;
use App\Models\TrialBalance;
use App\Models\TrialBalanceHistory;
use App\Models\TrialBalanceTotals;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class AddTrialBalance extends Component
{
    use WithFileUploads;

    public $tbName;
    public $tbType;
    public $tbData;
    public $tbDataTotals;
    public $debitGrandTotals;
    public $creditGrandTotals;
    public $isTbBalanced;
    public $tbDate;
    public $interimPeriod;
    public $quarter;

    public $tbList;
    public $updateExistingTbId;

    public $source;
    public $importedSpreadsheet;
    public $importedFromGL;

    public $spreadsheet = [];
    public $preview = [];
    public $month;
    public $year;
    public $months = [
        "1" => "January",
        "2" => "February",
        "3" => "March",
        "4" => "April",
        "5" => "May",
        "6" => "June",
        "7" => "July",
        "8" => "August",
        "9" => "September",
        "10" => "October",
        "11" => "November",
        "12" => "December"
    ];
    protected $rules = [
        "tbName" => "required|max:255",
        "tbDate" => "required|date",
        // "tbType" => "nullable|in:pre,post",
        // "importedSpreadsheet" => "required|file|mimes:xlsx,xls,ods",
        "interimPeriod" => "required|in:Monthly,Quarterly,Annual",
        "month" => "nullable",
        "quarter" => "nullable|in:Q1,Q2,Q3,Q4",
    ];

    public function mount()
    {
        // default values so user does not need to interact with the form and just save
        $this->tbDate = date('Y-m-d');
        $this->year = date('Y');

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
        $this->interimPeriod = trim($this->interimPeriod);

        // if ($this->interimPeriod === "Annual" && !$this->tbName) {
        //     $this->tbName = "Annual Trial Balance " . date('Y');
        //     $this->tbType = "pre";
        // } else if ($this->interimPeriod === "Quarterly" && !$this->tbName) {
        //     $this->tbName = "$this->quarter Trial Balance " . date('Y');
        // } else if (!$this->tbName) {
        //     $this->tbName = "Trial Balance " . date('Y-m');
        // }

        $this->validate();
        if ($this->tbData) {
            $tb = TrialBalance::create([
                "tb_name" => $this->tbName,
                "tb_type" => $this->tbType ?? null,
                "tb_status" => 'Draft',
                "interim_period" => $this->interimPeriod,
                "quarter" => $this->quarter,
                "approved" => false,
                // "tb_date" => $this->tbDate,
                "tb_month" => $this->month,
                "tb_year" => $this->year,
                "template_name" => 'tb_pre',
                "debit_grand_totals" => $this->debitGrandTotals,
                "credit_grand_totals" => $this->creditGrandTotals,
            ]);

            TrialBalanceHistory::create([
                "tb_id" => $tb->tb_id,
                "tb_data" => $this->tbData,
                "totals_data" => $this->tbDataTotals,
                // "date" => $this->tbDate
            ]);
        }

        if ($this->isTbBalanced) {
            session()->flash("success", "Trial Balance has been added.");
        } else {
            session()->flash("success", "Trial Balance has been added. Unbalanced Trial Balance accounts has been sent to General Ledger.");
        }

        $user = auth()->user()->first_name . " " . auth()->user()->last_name;
        activity()->withProperties(['user' => $user, 'role' => auth()->user()->role])->log("Add $this->tbName");
        $this->reset();
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

    public function importFromGL()
    {
        // load excel template
        if ($this->tbType) {
            $tbExportFormat = 'TB_' . strtoupper($this->tbType);
        } else {
            $tbExportFormat = "TB_PRE";
        }
        $filePath = 'public/uploads/' . $tbExportFormat . '.xlsx';
        $newFilePath = 'uploads/' . $tbExportFormat . '.xlsx';
        Storage::copy($filePath, $newFilePath);
        $spreadsheet = IOFactory::load(storage_path('app/' . $newFilePath));

        // load export config
        $tbExportConfig = DB::select('SELECT template FROM report_templates WHERE template_name = ?', [$tbExportFormat]);
        $jsonConfig = array_column($tbExportConfig, 'template')[0];
        $jsonConfig = json_decode($jsonConfig, true);

        // query data from GL
        $queryMonth = ltrim(date('m', strtotime($this->tbDate)), '0');
        $queryYear = date('Y', strtotime($this->tbDate));
        $this->interimPeriod = trim($this->interimPeriod);
        if ($this->interimPeriod == 'Quarterly') {
            $months = [];
            switch ($this->quarter) {
                case 'Q1':
                    $months = ['1', '2', '3'];
                    break;
                case 'Q2':
                    $months = ['4', '5', '6'];
                    break;
                case 'Q3':
                    $months = ['7', '8', '9'];
                    break;
                case 'Q4':
                    $months = ['10', '11', '12'];
                    break;
            }
            $res = DB::select("SELECT ls_account_title_code,ls_total_credit,ls_total_debit FROM ledgersheet_total_debit_credit WHERE ls_summary_month IN ('" . implode("','", $months) . "') AND ls_summary_year = '$this->year'");
        } else if ($this->interimPeriod == 'Monthly') {
            $res = DB::select("SELECT ls_account_title_code,ls_total_credit,ls_total_debit FROM ledgersheet_total_debit_credit WHERE ls_summary_month LIKE '$this->month%' AND ls_summary_year = '$this->year'");
        } else {
            $res = DB::select("SELECT ls_account_title_code,ls_total_credit,ls_total_debit FROM ledgersheet_total_debit_credit WHERE ls_summary_year = '$this->year'");
        }

        // queried data
        $credits = array_column($res, 'ls_total_credit');
        $debits = array_column($res, 'ls_total_debit');
        $accountCodes = array_column($res, 'ls_account_title_code');
        // GL accounts codes is formatted as: `<code> - <title>`
        // we only need the <code>
        for ($index = 0; $index < count($accountCodes); $index++) {
            $accountCodes[$index] = trim(explode('-', $accountCodes[$index])[0]);
        }

        // key : val == rowNumber : fsData
        $exportConfig = [];
        for ($i = 0; $i < count($accountCodes); $i++) {
            if (array_key_exists($accountCodes[$i], $jsonConfig)) {
                if (!array_key_exists($jsonConfig[$accountCodes[$i]], $exportConfig)) {
                    $exportConfig[$jsonConfig[$accountCodes[$i]]] = ['debit' => 0, 'credit' => 0];
                }
                $exportConfig[$jsonConfig[$accountCodes[$i]]]['credit'] += $credits[$i];
                $exportConfig[$jsonConfig[$accountCodes[$i]]]['debit'] += $debits[$i];
            }
        }

        // write to excel
        foreach ($exportConfig as $row => $value) {
            $spreadsheet->getActiveSheet()->setCellValue('F' . $row, $value['debit']);
            $spreadsheet->getActiveSheet()->setCellValue('H' . $row, $value['credit']);
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/' . $newFilePath));

        // imported from gl
        $this->importedFromGL = storage_path('app/' . $newFilePath);

        if (count($accountCodes) > 0) {
            $this->getTBData();
        }

        $this->source = [
            "accountCodes" => count($accountCodes),
            "debitGrandTotals" => $this->debitGrandTotals,
            "creditGrandTotals" => $this->creditGrandTotals,
        ];
    }

    private function getTBData()
    {
        // get data from GL
        $spreadsheet = IOFactory::load($this->importedFromGL);

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
        foreach ($totalsConfig as $title => $row) {
            $debit = $spreadsheet->getActiveSheet()->getCell("F" . $row)->getCalculatedValue();
            $credit = $spreadsheet->getActiveSheet()->getCell("H" . $row)->getCalculatedValue();
            $tbDataTotals[$title] = [
                "debit" => $debit,
                "credit" => $credit
            ];
        }

        $this->debitGrandTotals = $tbDataTotals['GRAND TOTALS']['debit'];
        $this->creditGrandTotals = $tbDataTotals['GRAND TOTALS']['credit'];
        $this->isTbBalanced = ($this->debitGrandTotals - $this->creditGrandTotals) == 0;

        $this->tbData = json_encode($tbData);
        $this->tbDataTotals = json_encode($tbDataTotals);

        session()->now("success", "Import successful!");
        // remove excel file
        unlink($this->importedFromGL);
    }

    public function resetImport()
    {
        if ($this->importedFromGL) {
            $this->reset(['tbData', 'tbDataTotals', 'importedFromGL', 'source', 'quarter', 'month']);
        } else {
            $this->reset(['quarter', 'month']);
        }
    }
    public function cancel()
    {
        return $this->redirect('/trial-balances', navigate: true);
    }

    public function render()
    {
        return view('livewire.trial-balance.add-trial-balance');
    }
}
