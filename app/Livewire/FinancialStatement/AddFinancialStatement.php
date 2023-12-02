<?php

namespace App\Livewire\FinancialStatement;

// use App\Imports\FinancialStatementImport;
use App\Models\FinancialStatement;
use App\Models\ReportTemplate;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Storage;

class AddFinancialStatement extends Component
{
    use WithFileUploads;

    public $fsName;
    public $fsType;
    public $fsData;
    public $date;
    public $interimPeriod;
    public $quarter;
    public $importedSpreadsheet;
    public $spreadsheet = [];
    public $preview = [];

    protected $rules = [
        "fsName" => "nullable|max:255",
        "date" => "required|date",
        "fsType" => "required|in:SFPO,SFPE,SCF",
        "importedSpreadsheet" => "required|file|mimes:xlsx,xls,ods",
        'interimPeriod' => 'required|in:Quarterly,Annual',
        'quarter' => 'nullable',
    ];

    public function mount()
    {
        // default values so user does not need to interact with the form and just save
        $this->date = date('Y-m-d');
        $this->interimPeriod = "Quarterly";
        $this->fsType = "SFPO"; // because it's the first value
    }

    public function add(){
        $fr_month = date('m', strtotime($this->date));

        if(!$this->fsName) {
            if ($this->interimPeriod === 'Quarterly') {
                $this->rules['quarter'] = 'required|in:Q1,Q2,Q3,Q4';
                $quarter = ceil($fr_month / 3);
                $this->quarter = "Q$quarter";
                $this->fsName = "Q$quarter Financial Statement " . date('Y');
            } else {
                $this->quarter = null;
                $this->fsName = "Annual Financial Statement " . date('Y');
            }
        }

        $this->processImportedSpreadsheet();
        $this->validate();
        if($this->importedSpreadsheet){
            FinancialStatement::create([
                "fs_type" => $this->fsType,
                "fs_data" => $this->fsData,
                "report_name" => $this->fsName,
                "interim_period" => $this->interimPeriod,
                "quarter" => $this->quarter,
                "report_status" => 'Draft',
                "approved" => false,
                "date" => $this->date,
                "template_name" => strtolower($this->fsType),
            ]);
            $this->reset();
        }
        $this->redirect('/financial-statements');
    }

    public function processImportedSpreadsheet()
    {
        $uploadedFile = $this->importedSpreadsheet;
        $path = Storage::disk('local')->putFileAs('uploads', $uploadedFile, $uploadedFile->getClientOriginalName());
        $spreadsheet = IOFactory::load(storage_path('app/'.$path));
        $sheet = $spreadsheet->getActiveSheet();
        
        $templateName = strtolower($this->fsType) . '_vals';
        $jsonMap = ReportTemplate::where('template_name', $templateName)->value('template');

        $column = ($this->fsType === 'SCF') ? 'E' : 'F';
        $rowValues = json_decode($jsonMap, true);
        foreach ($rowValues as &$row) {
            $row = $sheet->getCell($column . $row)->getValue() ?? 0;
        }
        $this->fsData = json_encode($rowValues);
    }

    public function render()
    {
        return view('livewire.financial-statement.add-financial-statement');
    }
}
