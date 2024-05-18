<?php

namespace App\Livewire\FinancialStatementCollection;

use App\Mail\FinancialReportEmail;
use App\Models\FinancialStatementCollection;
use App\Models\FinancialStatement;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Storage;
use DateTime;
use Illuminate\Support\Facades\DB;

class PreviewFinancialStatementCollection extends Component
{
    public FinancialStatementCollection $fsCollection;
    public $financialStatements = [];
    public $reportType = "fsc";
    public $filePath;
    public $exportableFilePath;
    public $reportStatusOptions = [];
    public $selectedStatusOption;
    // public $confirming = null;
    // public $editMode = false;
    // public $editedFSCName;
    // public $editedDate;
    // public $editedInterimPeriod;
    // public $editedQuarter;
    // public $editedApproved;
    // public $editedFSCStatus;
    public $isWriting = false;
    public $receiver = '';
    public $message = '';
    public $subject = '';
    protected $attachment;
    public $filename;
    protected $rules = [
        // 'editedFSCName' => 'nullable|max:255',
        // 'editedDate' => 'required|date',
        // 'editedInterimPeriod' => 'required|in:Quarterly,Annual',
        // 'editedQuarter' => 'nullable|in:Q1,Q2,Q3,Q4',
        'selectedStatusOption' => 'required|in:Draft,For Approval,Approved,Change Requested',
        'subject' => 'required',
        'receiver' => 'required|email',
        'message' => 'required',
        // 'editedApproved' => 'required|boolean',
    ];

    public function mount(){
        $fsc_id = Route::current()->parameter("collection_id");
        $this->fsCollection = FinancialStatementCollection::where('collection_id', $fsc_id)->get()->first();
        $query = FinancialStatement::where('collection_id', $this->fsCollection->collection_id)->get();

        foreach($query as $row){
            $this->financialStatements[$row->fs_type] = $row;
        }
        $this->filePath = 'public/uploads/FS.xlsx';
        $this->exportableFilePath = 'uploads/FS.xlsx';

        // default values
        // $this->editedFSCName = $this->fsCollection->collection_name;
        // $this->editedDate = $this->fsCollection->date;
        // $this->editedInterimPeriod = $this->fsCollection->interim_period;
        // $this->editedQuarter = $this->fsCollection->quarter;
        // $this->editedApproved = $this->fsCollection->approved;
        // $this->editedFSCStatus = $this->fsCollection->collection_status;
    }

    public function writeReport(){
        if($this->attachment or $this->isWriting){
            return;
        }
        $this->isWriting = true;
        $this->filename = $this->fsCollection->collection_name;


        Storage::copy($this->filePath, $this->exportableFilePath);

        $spreadsheet = IOFactory::load(storage_path('app/' . $this->exportableFilePath));

        // sfpo, sfpe, scf in order
        $fsDataResults = DB::select('SELECT fs_data FROM financial_statements WHERE collection_id = ?', [$this->fsCollection->collection_id]);
        $jsonData = array_column($fsDataResults, 'fs_data');
        // key : val == rowNumber : fsData
        $combinedData = array_map('json_decode', $jsonData, array_fill(0, count($jsonData), true));

        for ($i=0 ; $i<3 ; $i++) {
            $column = ($i === 2) ? 'E' : 'F'; 
            foreach ($combinedData[$i] as $row => $value) {
                $spreadsheet->getSheet($i)->setCellValue($column . $row, $value);
            }
            $editedYear = date('Y', strtotime($this->fsCollection->date));
            $date = [
                'Q1'=> "March 31, ".$editedYear,
                'Q2'=> "June 31, ".$editedYear,
                'Q3'=> "September 31, ".$editedYear,
                'Q4'=> "December 31, ".$editedYear,
            ];
            $dateHeader = $spreadsheet->getActiveSheet()->getCell('A6')->getValue();
            if ($this->fsCollection->interim_period === 'Quarterly') {
                $newDateHeader = str_replace('<date>', $date[$this->fsCollection->quarter], $dateHeader);
            } else {
                $newDateHeader = 'For the Year Ended December 31, '.$editedYear;
            }
            $spreadsheet->getSheet($i)->setCellValue('A6', $newDateHeader);
        }
        $this->attachment = new Xlsx($spreadsheet);
        $this->attachment->save(storage_path('app/'.$this->exportableFilePath));
        $this->isWriting = false;
    }

    public function mailReport(){
        if(!$this->attachment){
            $this->writeReport();
        }

        $this->validate();

        Mail::to($this->receiver)->send(new FinancialReportEmail($this->subject, $this->message, $this->filename, storage_path('app/'.$this->exportableFilePath)));

        session()->now("success", "Successfully mailed $this->filename");

        $this->reset('subject', 'receiver', 'message');
    }

    public function export(){
        if(!$this->attachment){
            $this->writeReport();
        }

        $headers = ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',];

        $this->attachment = null;
        session()->now("success", "Successfully exported Financial Statements");

        return response()->download(storage_path('app/'.$this->exportableFilePath), $this->filename.'.xlsx', $headers)->deleteFileAfterSend(true);
    }

    // public function export() {
    //     if($this->attachment or $this->isWriting){
    //         return;
    //     }
    //     $filePath = 'public/uploads/FS.xlsx';
    //     $newFilePath = 'uploads/FS.xlsx';
    //     Storage::copy($filePath, $newFilePath);

    //     $spreadsheet = IOFactory::load(storage_path('app/' . $newFilePath));

    //     // sfpo, sfpe, scf in order
    //     $fsDataResults = DB::select('SELECT fs_data FROM financial_statements WHERE collection_id = ?', [$this->fsCollection->collection_id]);
    //     $jsonData = array_column($fsDataResults, 'fs_data');
    //     // key : val == rowNumber : fsData
    //     $combinedData = array_map('json_decode', $jsonData, array_fill(0, count($jsonData), true));

    //     for ($i=0 ; $i<3 ; $i++) {
    //         $column = ($i === 2) ? 'E' : 'F'; 
    //         foreach ($combinedData[$i] as $row => $value) {
    //             $spreadsheet->getSheet($i)->setCellValue($column . $row, $value);
    //         }
    //         $editedYear = date('Y', strtotime($this->fsCollection->date));
    //         $date = [
    //             'Q1'=> "March 31, ".$editedYear,
    //             'Q2'=> "June 31, ".$editedYear,
    //             'Q3'=> "September 31, ".$editedYear,
    //             'Q4'=> "December 31, ".$editedYear,
    //         ];
    //         $dateHeader = $spreadsheet->getActiveSheet()->getCell('A6')->getValue();
    //         if ($this->fsCollection->interim_period === 'Quarterly') {
    //             $newDateHeader = str_replace('<date>', $date[$this->fsCollection->quarter], $dateHeader);
    //         } else {
    //             $newDateHeader = 'For the Year Ended December 31, '.$editedYear;
    //         }
    //         $spreadsheet->getSheet($i)->setCellValue('A6', $newDateHeader);
    //     }
    //     $writer = new Xlsx($spreadsheet);
    //     $writer->save(storage_path('app/'.$newFilePath));
    //     $headers = [
    //         'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    //     ];
    //     $filename = $this->fsCollection->collection_name;

    //     session()->now("success", "Successfully exported Financial Statements");
    //     return response()->download(storage_path('app/'.$newFilePath), $filename.'.xlsx', $headers)
    //         ->deleteFileAfterSend(true);
    // }

    // public function toggleEditMode()
    // {
    //     $this->editMode = !$this->editMode;
    // }

    public function updateFinancialStatementCollection()
    {
        $this->validateOnly('selectedStatusOption');
        // check if the report is already approved but changed to not approved
        // if ($this->fsCollection->approved) {
        //     if (!$this->editedApproved) {
        //         $this->editedFSCStatus = 'For Approval';
        //     }
        // }

        // if not approved in the first place but changed to not approved
        // if ($this->editedApproved) {
        //     $this->editedFSCStatus = 'Approved';
        // }

        // if ($this->editedInterimPeriod === "Annual") {
        //     $this->editedQuarter = null;
        // } else {
        //     $fs_month = date('m', strtotime($this->editedDate));
        //     $quarter = ceil($fs_month / 3);
        //     $this->editedQuarter = "Q$quarter";
        // }
        
        // update fields
        // $this->fsCollection->collection_name = $this->editedFSCName;
        // $this->fsCollection->date = $this->editedDate;
        // $this->fsCollection->interim_period = $this->editedInterimPeriod;
        // $this->fsCollection->quarter = $this->editedQuarter;
        $this->fsCollection->approved = $this->selectedStatusOption === "Approved";
        $this->fsCollection->collection_status = $this->selectedStatusOption;
        $this->fsCollection->save();

        session()->now("success", "Financial Statement Collection has been updated.");
        // exit edit mode
        // $this->editMode = false;
    }

    public function render()
    {
        if(auth()->user()->role === "accounting"){
            if(in_array($this->fsCollection->collection_status, ['Draft', 'Change Requested'])){
                $this->selectedStatusOption = "For Approval";
            } else {
                $this->selectedStatusOption = "Draft";
            }
        }

        if(auth()->user()->role === "ovpf"){
            if($this->fsCollection->collection_status === "Draft") {
                $this->selectedStatusOption = "For Approval";
            } 
            
            if($this->fsCollection->collection_status === "Change Requested"){
                $this->selectedStatusOption = "Approved";
            }

            if($this->fsCollection->collection_status === "For Approval") {
                $this->reportStatusOptions = ["Approved", "Change Requested"];
                $this->selectedStatusOption = "Approved";
            }
        }
        
        return view('livewire.financial-statement-collection.preview-financial-statement-collection',
            ["statusColor" => strtolower(join("", explode(" ",$this->fsCollection->collection_status)))]
        );
    }
}
