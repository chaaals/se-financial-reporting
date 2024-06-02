<?php

namespace App\Livewire\TrialBalance;

use App\Exports\TrialBalanceExport;
use App\Mail\FinancialReportEmail;
use App\Models\TrialBalance;
use App\Models\TrialBalanceHistory;
use App\Models\TrialBalanceTotals;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PreviewTrialBalance extends Component
{
    public TrialBalance $trial_balance;
    public $tbExportFormat = "TB_PRE";
    public $filePath;
    public $exportableFilePath;
    public $all_tb_data;
    public $trial_balance_data;
    public $trial_balance_totals;
    public $active_trial_balance_data = 0;
    public $reportType = "tb";
    public $reportStatusOptions = [];
    public $selectedStatusOption;
    public $debitTotals = [];
    public $creditTotals = [];
    public $debitGrandTotals = 0;
    public $creditGrandTotals = 0;
    public $isBalanced;
    public $isWriting = false;

    public $receiver = '';
    public $message = '';
    public $subject = '';
    protected $attachment;
    public $filename;

    protected $rules = [
        // 'editedReportName' => 'nullable|max:255',
        // 'editedDate' => 'required|date',
        // 'editedInterimPeriod' => 'required|in:Quarterly,Annual',
        // 'editedQuarter' => 'nullable|in:Q1,Q2,Q3,Q4',
        'selectedStatusOption' => 'required|in:Draft,For Approval,Approved,Change Requested',
        'subject' => 'required',
        'receiver' => 'required|email',
        'message' => 'required',
        // 'editedApproved' => 'required|boolean',
    ];

    protected $listeners = ["rebalance" => "refresh"];

    public function mount()
    {
        $tb_id = Route::current()->parameter("tb_id");
        // $query = TrialBalance::with('tbData')->where('tb_id', $tb_id)->get();
        $query = TrialBalance::withTrashed()->with('tbData')->where('tb_id', $tb_id)->get();
        // $tb_data_query = TrialBalanceHistory::where('tb_id', $tb_id)->get();

        foreach ($query as $tb) {
            $this->trial_balance = $tb;
        }

        // $this->trial_balance->orderByDesc()

        $this->all_tb_data = $this->trial_balance->getRelation('tbData');

        $this->trial_balance_data = $this->all_tb_data[$this->active_trial_balance_data];
        $this->isBalanced = ($this->trial_balance->debit_grand_totals - $this->trial_balance->credit_grand_totals) == 0;

        if ($this->trial_balance->tb_type) {
            $this->tbExportFormat = 'TB_' . strtoupper($this->trial_balance->tb_type);
        }

        $this->filePath = 'public/uploads/' . $this->tbExportFormat . '.xlsx';
        $this->exportableFilePath = 'uploads/' . $this->tbExportFormat . '.xlsx';
    }


    public function setActiveTrialBalanceData(int $index)
    {
        $this->active_trial_balance_data = $index;
        $this->trial_balance_data = $this->all_tb_data[$index];

        $this->debitTotals = [];
        $this->creditTotals = [];
        $this->debitGrandTotals = 0;
        $this->creditGrandTotals = 0;
    }

    public function writeReport()
    {
        if ($this->attachment or $this->isWriting) {
            return;
        }
        $this->isWriting = true;
        $this->filename = $this->trial_balance->tb_name;

        Storage::copy($this->filePath, $this->exportableFilePath);
        $spreadsheet = IOFactory::load(storage_path('app/' . $this->exportableFilePath));

        $activeTbData = $this->all_tb_data[$this->active_trial_balance_data]['tb_data'];
        // $jsonData = array_column($activeTbData, 'tb_data')[0];
        $jsonData = json_decode($activeTbData, true);

        $tbExportConfig = DB::select('SELECT template FROM report_templates WHERE template_name = ?', [$this->tbExportFormat]);
        $jsonConfig = array_column($tbExportConfig, 'template')[0];
        $jsonConfig = json_decode($jsonConfig, true);

        // key : val == rowNumber : fsData
        $exportConfig = [];
        foreach ($jsonConfig as $key => $value) {
            if (array_key_exists($key, $jsonData)) {
                $exportConfig[$value] = $jsonData[$key];
            }
        }

        // write to excel
        foreach ($exportConfig as $row => $value) {
            $spreadsheet->getActiveSheet()->setCellValue('F' . $row, $value['debit']);
            $spreadsheet->getActiveSheet()->setCellValue('H' . $row, $value['credit']);
        }

        // set date on excel
        $tbYear = date('Y', strtotime($this->trial_balance->date));
        $date = [
            'Q1' => "March 31, " . $tbYear,
            'Q2' => "June 31, " . $tbYear,
            'Q3' => "September 31, " . $tbYear,
            'Q4' => "December 31, " . $tbYear,
        ];
        $dateHeader = $spreadsheet->getActiveSheet()->getCell('A6')->getValue();
        if ($this->trial_balance->interim_period === 'Quarterly') {
            $newDateHeader = str_replace('<date>', $date[$this->trial_balance->quarter], $dateHeader);
        } else if ($this->trial_balance->interim_period === 'Monthly') {
            $tb_month = date('m', strtotime($this->trial_balance->date));
            $quarter = ceil($tb_month / 3);
            $quarter = "Q$quarter";
            $newDateHeader = str_replace('<date>', $date[$quarter], $dateHeader);
        } else {
            $newDateHeader = 'For the Year Ended December 31, ' . $tbYear;
        }
        $spreadsheet->getActiveSheet()->setCellValue('A6', $newDateHeader);

        $this->attachment = new Xlsx($spreadsheet);
        $this->attachment->save(storage_path('app/' . $this->exportableFilePath));

        $this->isWriting = false;
    }

    public function mailReport()
    {
        if (!$this->attachment) {
            $this->writeReport();
        }

        $this->validate();

        Mail::to($this->receiver)->send(new FinancialReportEmail($this->subject, $this->message, $this->filename, storage_path('app/' . $this->exportableFilePath)));

        $user = auth()->user()->first_name . " " . auth()->user()->last_name;
        activity()->withProperties(['user' => $user, 'role' => auth()->user()->role])->log("Mailed $this->filename to $this->receiver");

        session()->now("success", "Successfully mailed $this->filename");

        $this->reset('subject', 'receiver', 'message');
    }

    public function export()
    {
        if (!$this->attachment) {
            $this->writeReport();
        }

        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        $user = auth()->user()->first_name . " " . auth()->user()->last_name;
        activity()->withProperties(['user' => $user, 'role' => auth()->user()->role])->log("Exported $this->filename");

        session()->now("success", "Successfully exported file.");

        $this->attachment = null;
        return response()->download(storage_path('app/' . $this->exportableFilePath), $this->filename . '.xlsx', $headers)
            ->deleteFileAfterSend(true);
    }

    // public function export() {
    //     if ($this->trial_balance->tb_type) {
    //         $tbExportFormat = 'TB_'.strtoupper($this->trial_balance->tb_type);
    //     } else {
    //         $tbExportFormat = "TB_PRE";
    //     }
    //     $filePath = 'public/uploads/' . $tbExportFormat . '.xlsx';
    //     $newFilePath = 'uploads/' . $tbExportFormat . '.xlsx';
    //     Storage::copy($filePath, $newFilePath);

    //     $spreadsheet = IOFactory::load(storage_path('app/' . $newFilePath));

    //     $activeTbData = $this->all_tb_data[$this->active_trial_balance_data]['tb_data'];
    //     // $jsonData = array_column($activeTbData, 'tb_data')[0];
    //     $jsonData = json_decode($activeTbData, true);

    //     $tbExportConfig = DB::select('SELECT template FROM report_templates WHERE template_name = ?', [$tbExportFormat]);
    //     $jsonConfig = array_column($tbExportConfig, 'template')[0];
    //     $jsonConfig = json_decode($jsonConfig, true);

    //     // key : val == rowNumber : fsData
    //     $exportConfig = [];
    //     foreach ($jsonConfig as $key => $value) {
    //         if (array_key_exists($key, $jsonData)) {
    //             $exportConfig[$value] = $jsonData[$key];
    //         }
    //     }

    //     // write to excel
    //     foreach ($exportConfig as $row => $value) {
    //         $spreadsheet->getActiveSheet()->setCellValue('F' . $row, $value['debit']);
    //         $spreadsheet->getActiveSheet()->setCellValue('H' . $row, $value['credit']);
    //     }

    //     // set date on excel
    //     $tbYear = date('Y', strtotime($this->trial_balance->date));
    //     $date = [
    //         'Q1'=> "March 31, ".$tbYear,
    //         'Q2'=> "June 31, ".$tbYear,
    //         'Q3'=> "September 31, ".$tbYear,
    //         'Q4'=> "December 31, ".$tbYear,
    //     ];
    //     $dateHeader = $spreadsheet->getActiveSheet()->getCell('A6')->getValue();
    //     if ($this->trial_balance->interim_period === 'Quarterly') {
    //         $newDateHeader = str_replace('<date>', $date[$this->trial_balance->quarter], $dateHeader);
    //     } else if ($this->trial_balance->interim_period === 'Monthly') {
    //         $tb_month = date('m', strtotime($this->trial_balance->date));
    //         $quarter = ceil($tb_month / 3);
    //         $quarter = "Q$quarter";
    //         $newDateHeader = str_replace('<date>', $date[$quarter], $dateHeader);
    //     } else {
    //         $newDateHeader = 'For the Year Ended December 31, '.$tbYear;
    //     }
    //     $spreadsheet->getActiveSheet()->setCellValue('A6', $newDateHeader);

    //     $writer = new Xlsx($spreadsheet);
    //     $writer->save(storage_path('app/'.$newFilePath));
    //     $headers = [
    //         'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    //     ];
    //     $filename = $this->trial_balance->tb_name;

    //     $sessionMessage = "";
    //     if($this->isBalanced && $this->trial_balance->approved && auth()->user()->role === "accounting"){
    //         $reciever = 'cmlching2021@plm.edu.ph';
    //         Mail::to($reciever)->send(new FinancialReportEmail(storage_path('app/'.$newFilePath), $filename));
    //         $sessionMessage = "Successfully exported and sent Trial Balance.";
    //     } else {
    //         $sessionMessage = "Successfully exported Trial Balance.";
    //     }

    //     session()->now("success", $sessionMessage);
    //     return response()->download(storage_path('app/'.$newFilePath), $filename.'.xlsx', $headers)
    //         ->deleteFileAfterSend(true);
    // }

    public function confirmDelete($tbId)
    {
        $this->confirming = $tbId;
    }

    public function deleteTrialBalance($tbId)
    {
        // delete by ID
        TrialBalance::find($tbId)->delete();
        $this->reset('confirming');
        $this->redirect("/trial-balances");
    }

    public function toggleEditMode()
    {
        $this->editMode = !$this->editMode;
    }

    public function rebalance()
    {
        // load excel template
        if ($this->trial_balance->tb_type) {
            $tbExportFormat = 'TB_' . strtoupper($this->trial_balance->tb_type);
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
        $queryMonth = ltrim(date('m', strtotime($this->trial_balance->tb_date)), '0');
        $queryYear = date('Y', strtotime($this->trial_balance->tb_date));
        $this->trial_balance->interim_period = trim($this->trial_balance->interim_period);
        if ($this->trial_balance->interim_period == 'Quarterly') {
            $months = [];
            switch ($this->trial_balance->quarter) {
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
            $res = DB::select("SELECT ls_account_title_code,ls_total_credit,ls_total_debit FROM ledgersheet_total_debit_credit WHERE ls_summary_month IN ('" . implode("','", $months) . "') AND ls_summary_year = '$queryYear'");
        } else if ($this->trial_balance->interim_period == 'Monthly') {
            $res = DB::select("SELECT ls_account_title_code,ls_total_credit,ls_total_debit FROM ledgersheet_total_debit_credit WHERE ls_summary_month LIKE '$queryMonth%' AND ls_summary_year = '$queryYear'");
        } else {
            $res = DB::select("SELECT ls_account_title_code,ls_total_credit,ls_total_debit FROM ledgersheet_total_debit_credit WHERE ls_summary_year = '$queryYear'");
        }

        // queried data
        $credits = array_column($res, 'ls_total_credit');
        $debits = array_column($res, 'ls_total_debit');
        $accountCodes = array_column($res, 'ls_account_title_code');

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

        // load written xlsx
        $spreadsheet = IOFactory::load(storage_path('app/' . $newFilePath));

        // re-get tbdata from xlsx
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

        // get totals data from xlsx
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
        $this->isBalanced = ($this->debitGrandTotals - $this->creditGrandTotals) == 0;

        $rebalanced = json_encode($tbData);
        $rebalancedTotals = json_encode($tbDataTotals);

        TrialBalanceHistory::create([
            "tb_id" => $this->trial_balance->tb_id,
            "tb_data" => $rebalanced,
            "totals_data" => $rebalancedTotals,
            "date" => $this->trial_balance->tb_date
        ]);

        $user = auth()->user()->first_name . " " . auth()->user()->last_name;
        activity()->withProperties(['user' => $user, 'role' => auth()->user()->role])->log("Rebalanced $this->filename");

        session()->now("success", "Trial Balance has been rebalanced");
        unlink(storage_path('app/' . $newFilePath));
        $this->refetch();
    }

    public function refetch()
    {
        $query = TrialBalance::with('tbData')->orderBy('created_at', 'desc')->where('tb_id', $this->trial_balance->tb_id)->get();
        // $tb_data_query = TrialBalanceHistory::where('tb_id', $tb_id)->get();

        foreach ($query as $tb) {
            $this->trial_balance = $tb;
        }

        $this->all_tb_data = $this->trial_balance->getRelation('tbData')->toArray();
        $this->active_trial_balance_data = 0;
        $this->trial_balance_data = $this->all_tb_data[0];
    }

    public function updateTrialBalance()
    {
        // dd('works');
        $this->validateOnly('selectedStatusOption');
        // check if the report is already approved but changed to not approved
        // if ($this->trial_balance->approved) {
        //     if (!$this->editedApproved) {
        //         $this->editedReportStatus = 'For Approval';
        //     }
        // }

        // if not approved in the first place but changed to not approved
        // if ($this->editedApproved) {
        //     $this->editedReportStatus = 'Approved';
        // }

        // if ($this->editedInterimPeriod === "Annual") {
        //     $this->editedQuarter = null;
        // } else {
        //     $tb_month = date('m', strtotime($this->editedDate));
        //     $quarter = ceil($tb_month / 3);
        //     $this->editedQuarter = "Q$quarter";
        // }

        // update fields
        // $this->trial_balance->report_name = $this->editedReportName;
        // $this->trial_balance->date = $this->editedDate;
        // $this->trial_balance->interim_period = $this->editedInterimPeriod;
        // $this->trial_balance->quarter = $this->editedQuarter;
        $this->trial_balance->approved = $this->selectedStatusOption === "Approved";
        $this->trial_balance->tb_status = $this->selectedStatusOption;
        $this->trial_balance->save();

        $user = auth()->user()->first_name . " " . auth()->user()->last_name;
        activity()->withProperties(['user' => $user, 'role' => auth()->user()->role])->log("Updated $this->filename");

        session()->now("success", "Trial Balance has been updated.");
        // exit edit mode
        // $this->editMode = false;
    }


    public function render()
    {
        // if($this->trial_balance->trashed()){
        //     dd('Report is archived');
        // }

        if (auth()->user()->role === "accounting") {
            if (in_array($this->trial_balance->tb_status, ['Draft', 'Change Requested'])) {
                $this->selectedStatusOption = "For Approval";
            } else {
                $this->selectedStatusOption = "Draft";
            }
        }

        if (auth()->user()->role === "ovpf") {
            if ($this->trial_balance->tb_status === "Draft") {
                $this->selectedStatusOption = "For Approval";
            }

            if ($this->trial_balance->tb_status === "Change Requested") {
                $this->selectedStatusOption = "Approved";
            }

            if ($this->trial_balance->tb_status === "For Approval") {
                if ($this->isBalanced) {
                    $this->reportStatusOptions = ["Approved", "Change Requested"];
                    $this->selectedStatusOption = "Approved";
                } else {
                    $this->selectedStatusOption = "Change Requested";
                }
            }
        }

        return view(
            'livewire.trial-balance.preview-trial-balance',
            [
                "statusColor" => strtolower(join("", explode(" ", $this->trial_balance->tb_status))),
                "numModifications" => count($this->all_tb_data)
            ]
        );
    }
}
