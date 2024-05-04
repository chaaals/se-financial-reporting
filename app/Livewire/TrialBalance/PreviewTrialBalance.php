<?php

namespace App\Livewire\TrialBalance;

use App\Exports\TrialBalanceExport;
use App\Mail\FinancialReportEmail;
use App\Models\TrialBalance;
use App\Models\TrialBalanceHistory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PreviewTrialBalance extends Component
{
    public TrialBalance $trial_balance;
    public $all_tb_data;
    public $trial_balance_data;
    public $active_trial_balance_data = 0;
    public $reportType = "tb";
    public $reportStatusOptions = [];
    public $selectedStatusOption;
    public $debitTotals = [];
    public $creditTotals = [];
    public $debitGrandTotals = 0;
    public $creditGrandTotals = 0;
    public $isBalanced;
    // public $confirming = null;
    // public $editMode = false;
    // public $editedReportName;
    // public $editedDate;
    // public $editedInterimPeriod;
    // public $editedQuarter;
    // public $editedApproved;
    // public $editedReportStatus;

    protected $rules = [
        // 'editedReportName' => 'nullable|max:255',
        // 'editedDate' => 'required|date',
        // 'editedInterimPeriod' => 'required|in:Quarterly,Annual',
        // 'editedQuarter' => 'nullable|in:Q1,Q2,Q3,Q4',
        'selectedStatusOption' => 'required|in:Draft,For Approval,Approved,Change Requested',
        // 'editedApproved' => 'required|boolean',
    ];

    protected $listeners = ["rebalance" => "refresh"];

    public function mount(){
        $tb_id = Route::current()->parameter("tb_id");
        $query = TrialBalance::with('tbData')->where('tb_id', $tb_id)->get();
        // $tb_data_query = TrialBalanceHistory::where('tb_id', $tb_id)->get();

        foreach($query as $tb){
            $this->trial_balance= $tb;
        }

        // $this->trial_balance->orderByDesc()

        $this->all_tb_data = $this->trial_balance->getRelation('tbData');

        $this->trial_balance_data = $this->all_tb_data[$this->active_trial_balance_data];
        $this->isBalanced = ($this->trial_balance->debit_grand_totals + $this->trial_balance->credit_grand_totals) == 0; 

        // default values
        // $this->editedReportName = $this->trial_balance->report_name;
        // $this->editedDate = $this->trial_balance->date;
        // $this->editedInterimPeriod = $this->trial_balance->interim_period;
        // $this->editedQuarter = $this->trial_balance->quarter;
        // $this->editedApproved = $this->trial_balance->approved;
        // $this->editedReportStatus = $this->trial_balance->report_status;
    }

    public function setActiveTrialBalanceData(int $index){
        $this->active_trial_balance_data = $index;
        $this->trial_balance_data = $this->all_tb_data[$index];

        $this->debitTotals = [];
        $this->creditTotals = [];
        $this->debitGrandTotals = 0;
        $this->creditGrandTotals = 0;

    }

    public function export() {
        if ($this->trial_balance->tb_type) {
            $tbExportFormat = 'TB_'.strtoupper($this->trial_balance->tb_type);
        } else {
            $tbExportFormat = "TB_PRE";
        }
        $filePath = 'public/uploads/' . $tbExportFormat . '.xlsx';
        $newFilePath = 'uploads/' . $tbExportFormat . '.xlsx';
        Storage::copy($filePath, $newFilePath);

        $spreadsheet = IOFactory::load(storage_path('app/' . $newFilePath));

        $activeTbData = $this->all_tb_data[$this->active_trial_balance_data]['tb_data'];
        // $jsonData = array_column($activeTbData, 'tb_data')[0];
        $jsonData = json_decode($activeTbData, true);

        $tbExportConfig = DB::select('SELECT template FROM report_templates WHERE template_name = ?', [$tbExportFormat]);
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
            'Q1'=> "March 31, ".$tbYear,
            'Q2'=> "June 31, ".$tbYear,
            'Q3'=> "September 31, ".$tbYear,
            'Q4'=> "December 31, ".$tbYear,
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
            $newDateHeader = 'For the Year Ended December 31, '.$tbYear;
        }
        $spreadsheet->getActiveSheet()->setCellValue('A6', $newDateHeader);

        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/'.$newFilePath));
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];
        $filename = $this->trial_balance->tb_name;
        
        $sessionMessage = "";
        if($this->isBalanced && $this->trial_balance->approved && auth()->user()->role === "accounting"){
            $reciever = 'cmlching2021@plm.edu.ph';
            Mail::to($reciever)->send(new FinancialReportEmail(storage_path('app/'.$newFilePath), $filename));
            $sessionMessage = "Successfully exported and sent Trial Balance.";
        } else {
            $sessionMessage = "Successfully exported Trial Balance.";
        }
        
        session()->now("success", $sessionMessage);
        return response()->download(storage_path('app/'.$newFilePath), $filename.'.xlsx', $headers)
            ->deleteFileAfterSend(true);
    }

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

    public function rebalance(){
        // TODO: Add logic that refetches GL
        $tbData = $this->trial_balance_data["tb_data"];
        $rebalanced = json_decode($tbData, true);

        foreach($rebalanced as $code=>$value){
            if (rand(0,1) < 0.25){
                $rebalanced[$code]['debit'] = rand(1, 500);
                $rebalanced[$code]['credit'] = rand(1, 500);
            }
        }
    
        $rebalanced = json_encode($rebalanced);

        TrialBalanceHistory::create([
            "tb_id" => $this->trial_balance->tb_id,
            "tb_data" => $rebalanced,
            "date" => $this->trial_balance->tb_date
        ]);

        session()->now("success", "Trial Balance has been rebalanced");
        $this->refetch();
    }

    public function refetch(){
        $query = TrialBalance::with('tbData')->orderBy('created_at', 'desc')->where('tb_id', $this->trial_balance->tb_id)->get();
        // $tb_data_query = TrialBalanceHistory::where('tb_id', $tb_id)->get();

        foreach($query as $tb){
            $this->trial_balance= $tb;
        }

        $this->all_tb_data = $this->trial_balance->getRelation('tbData')->toArray();
        $this->active_trial_balance_data = 0;
        $this->trial_balance_data = $this->all_tb_data[0];
    }

    public function updateTrialBalance()
    {
        $this->validate();
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

        session()->now("success", "Trial Balance has been updated.");
        // exit edit mode
        // $this->editMode = false;
    }

    #[On('add-value')]
    public function addValue(string $type, $payload){
        if($type == 'debit'){
            array_push($this->debitTotals, $payload);
            // $this->debitGrandTotals += $payload;
        }
        if($type == 'credit'){
            array_push($this->creditTotals, $payload);
            // $this->creditGrandTotals += $payload;
        }
    }


    public function render()
    {
        if(auth()->user()->role === "accounting"){
            if(in_array($this->trial_balance->tb_status, ['Draft', 'Change Requested'])){
                $this->selectedStatusOption = "For Approval";
            } else {
                $this->selectedStatusOption = "Draft";
            }
        }

        if(auth()->user()->role === "ovpf"){
            if($this->trial_balance->tb_status === "Draft") {
                $this->selectedStatusOption = "For Approval";
            } 
            
            if($this->trial_balance->tb_status === "Change Requested"){
                $this->selectedStatusOption = "Approved";
            }

            if($this->trial_balance->tb_status === "For Approval") {
                if($this->isBalanced){
                    $this->reportStatusOptions = ["Approved", "Change Requested"];
                    $this->selectedStatusOption = "Approved";
                } else {
                    $this->selectedStatusOption = "Change Requested";
                }
            }
        }

        return view('livewire.trial-balance.preview-trial-balance',
            ["statusColor" => strtolower(join("", explode(" ",$this->trial_balance->tb_status))),
            "numModifications" => count($this->all_tb_data)
            ]
        );
    }
}
