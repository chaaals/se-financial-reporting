<?php

namespace App\Livewire\TrialBalance;

use App\Exports\TrialBalanceExport;
use App\Models\TrialBalance;
use App\Models\TrialBalanceHistory;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PreviewTrialBalance extends Component
{
    public TrialBalance $trial_balance;
    public $trial_balance_data;
    public $reportType = "tb";
    public $reportStatusOptions = [];
    public $selectedStatusOption;
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

    public function mount(){
        $tb_id = Route::current()->parameter("tb_id");
        $query = TrialBalance::with('tbData')->where('tb_id', $tb_id)->get();
        // $tb_data_query = TrialBalanceHistory::where('tb_id', $tb_id)->get();

        foreach($query as $tb){
            $this->trial_balance= $tb;
        }

        $this->trial_balance_data = $this->trial_balance->getRelation('tbData')[0];

        // default values
        // $this->editedReportName = $this->trial_balance->report_name;
        // $this->editedDate = $this->trial_balance->date;
        // $this->editedInterimPeriod = $this->trial_balance->interim_period;
        // $this->editedQuarter = $this->trial_balance->quarter;
        // $this->editedApproved = $this->trial_balance->approved;
        // $this->editedReportStatus = $this->trial_balance->report_status;
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

        $tbDataResults = DB::select('SELECT tb_data FROM trial_balances WHERE tb_id = ?', [$this->trial_balance->tb_id]);
        $jsonData = array_column($tbDataResults, 'tb_data')[0];
        $jsonData = json_decode($jsonData, true);

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

        session()->now("success", "Successfully exported Trial Balance");

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
                $this->reportStatusOptions = ["Approved", "Change Requested"];
                $this->selectedStatusOption = "Approved";
            }
        }

        return view('livewire.trial-balance.preview-trial-balance',
            ["statusColor" => strtolower(join("", explode(" ",$this->trial_balance->tb_status)))]
        );
    }
}
