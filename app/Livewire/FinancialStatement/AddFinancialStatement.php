<?php

namespace App\Livewire\FinancialStatement;

// use App\Imports\FinancialStatementImport;
use App\Models\FinancialStatement;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddFinancialStatement extends Component
{
    use WithFileUploads;

    public $fsName;
    public $fsType;
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

        if ($this->interimPeriod === 'Quarterly') {
            $this->rules['quarter'] = 'required|in:Q1,Q2,Q3,Q4';
            $quarter = ceil($fr_month / 3);
            $this->quarter = "Q$quarter";
            $this->fsName = "Q$quarter Financial Statement " . date('Y');
        } else {
            $this->quarter = null;
            $this->fsName = "Annual Financial Statement " . date('Y');
        }

        $this->validate();
        if($this->spreadsheet){
            FinancialStatement::create([
                "fs_type" => $this->fsType,
                // "tb_data" => json_encode($this->spreadsheet),
                "fs_data" => '{"sample":"content"}',
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

    // public function previewSpreadsheet(){
    //     $path = $this->importedSpreadsheet->getRealPath();
        
    //     $this->spreadsheet = (new FinancialStatementImport)->toArray($path)[0];
        
    //     $this->preview["headers"] = array_slice($this->spreadsheet,5,1);
    //     $this->preview["data"] = array_slice($this->spreadsheet,6);
    // }

    public function render()
    {
        // if($this->importedSpreadsheet){
        //     $this->previewSpreadsheet();
        // }

        return view('livewire.financial-statement.add-financial-statement');
    }
}
