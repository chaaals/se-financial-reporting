<?php

namespace App\Livewire\FinancialStatement;

// use App\Exports\FinancialStatementExport;
use App\Models\FinancialStatement;
use App\Models\ReportTemplate;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Storage;

class PreviewFinancialStatement extends Component
{
    public FinancialStatement $financialStatement;
    public $confirming = null;
    public $editMode = false;
    public $editedReportName;
    public $editedFSType;
    public $editedDate;
    public $editedInterimPeriod;
    public $editedQuarter;
    public $editedApproved;
    public $editedReportStatus;

    protected $rules = [
        'editedReportName' => 'nullable|max:255',
        'editedFSType' => 'required|in:SFPE,SFPO,SCF',
        'editedDate' => 'required|date',
        'editedInterimPeriod' => 'required|in:Quarterly,Annual',
        'editedQuarter' => 'nullable|in:Q1,Q2,Q3,Q4',
        'editedReportStatus' => 'required|in:Draft,For Approval,Approved',
        'editedApproved' => 'required|boolean',
    ];

    public function mount(){
        $fs_id = Route::current()->parameter("statement_id");
        $query = FinancialStatement::where('statement_id', $fs_id)->get();

        foreach($query as $fs){
            $this->financialStatement= $fs;
        }

        // default values
        $this->editedReportName = $this->financialStatement->report_name;
        $this->editedDate = $this->financialStatement->date;
        $this->editedInterimPeriod = $this->financialStatement->interim_period;
        $this->editedQuarter = $this->financialStatement->quarter;
        $this->editedApproved = $this->financialStatement->approved;
        $this->editedReportStatus = $this->financialStatement->report_status;
        $this->editedFSType = $this->financialStatement->fs_type;
        
    }

    public function export() {
        $filePath = 'public/uploads/'.$this->editedFSType.'.xlsx';
        $newFilePath = 'uploads/'.$this->editedFSType.'.xlsx';
        Storage::copy($filePath, $newFilePath);

        $spreadsheet = IOFactory::load(storage_path('app/' . $newFilePath));

        // Get row numbers from the report_template table based on fsType
        $templateName = strtolower($this->editedFSType) . '_vals';
        $jsonMap = ReportTemplate::where('template_name', $templateName)->value('template');
        $rowNumbers = array_values(json_decode($jsonMap, true));
        $fsData = array_values(json_decode($this->financialStatement->fs_data));
        $combinedData = array_combine($rowNumbers, $fsData);
        $column = ($this->editedFSType === 'SCF') ? 'E' : 'F';
        
        foreach ($combinedData as $row => $value) {
            $spreadsheet->getActiveSheet()->setCellValue($column . $row, $value);
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/'.$newFilePath));
        
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];
        $filename = $this->financialStatement->report_name;
        return response()->download(storage_path('app/'.$newFilePath), $filename.'.xlsx', $headers)
            ->deleteFileAfterSend(true);
    }

    public function confirmDelete($tbId)
    {
        $this->confirming = $tbId;
    }

    public function deleteFinancialStatement($tbId)
    {
        // delete by ID
        FinancialStatement::find($tbId)->delete();
        $this->reset('confirming');
        $this->redirect("/financial-statements");
    }

    public function toggleEditMode()
    {
        $this->editMode = !$this->editMode;
    }

    public function updateFinancialStatement()
    {
        $this->validate();
        // check if the report is already approved but changed to not approved
        if ($this->financialStatement->approved) {
            if (!$this->editedApproved) {
                $this->editedReportStatus = 'For Approval';
            }
        }

        // if not approved in the first place but changed to not approved
        if ($this->editedApproved) {
            $this->editedReportStatus = 'Approved';
        }

        if ($this->editedInterimPeriod === "Annual") {
            $this->editedQuarter = null;
        } else {
            $fs_month = date('m', strtotime($this->editedDate));
            $quarter = ceil($fs_month / 3);
            $this->editedQuarter = "Q$quarter";
        }
        
        // update fields
        $this->financialStatement->report_name = $this->editedReportName;
        $this->financialStatement->fs_type = $this->editedFSType;
        $this->financialStatement->date = $this->editedDate;
        $this->financialStatement->interim_period = $this->editedInterimPeriod;
        $this->financialStatement->quarter = $this->editedQuarter;
        $this->financialStatement->approved = $this->editedApproved;
        $this->financialStatement->report_status = $this->editedReportStatus;
        $this->financialStatement->template_name = strtolower($this->editedFSType);
        $this->financialStatement->save();

        // exit edit mode
        $this->editMode = false;
    }

    public function render()
    {
        return view('livewire.financial-statement.preview-financial-statement');
    }
}
