<?php

namespace App\Livewire\TrialBalance;

use App\Imports\TrialBalanceImport;
use App\Models\TrialBalance;
use App\Models\FinancialReport;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddTrialBalance extends Component
{
    use WithFileUploads;

    public $tbName;
    public $tbType;
    public $date;
    public $fiscalYear;
    public $interimPeriod;
    public $quarter;
    
    public $importedSpreadsheet;

    public $spreadsheet = [];
    public $preview = [];
    protected $rules = [
        "tbName" => "nullable|max:255",
        "date" => "required|date",
        "tbType" => "nullable|in:pre,post",
        // "importedSpreadsheet" => "required|file|mimes:xlsx,xls,ods",
        'interimPeriod' => 'required|in:Quarterly,Annual',
        'quarter' => 'nullable',
        'fiscalYear' => 'required',
    ];

    public function mount()
    {
        // default values so user does not need to interact with the form and just save
        $this->date = date('Y-m-d');
        $this->interimPeriod = "Quarterly";
    }

    public function add(){
        $fr_month = date('m', strtotime($this->date));
        $reportName = null;

        if ($this->interimPeriod === 'Quarterly') {
            $this->rules['quarter'] = 'required|in:Q1,Q2,Q3,Q4';
            $quarter = ceil($fr_month / 3);
            $this->quarter = "Q$quarter";
            $reportName = "Q$quarter Financial Report " . date('Y');
        } else {
            $this->quarter = null;
            if ($this->interim_period === "Annual") {
                $this->reportName = "Annual Financial Report " . date('Y');
                $this->tbType = "pre";
            } else {
                $this->reportName = "Financial Report " . date('Y-m');
            }
        }
        $this->fiscalYear = date('Y', strtotime($this->date));

        $this->validate();
        if($this->spreadsheet){
            $financialReport = FinancialReport::create([
                "report_name" => $reportName,
                "fiscal_year" => $this->fiscalYear,
                "interim_period" => $this->interimPeriod,
                "quarter" => $this->quarter,
                "report_status" => 'Draft',
                "approved" => false,
                "date" => $this->date,
            ]);
            TrialBalance::create([
                "tb_type" => $this->tbType ?? null,
                "tb_data" => json_encode($this->spreadsheet),
                "report_id" => $financialReport->report_id,
            ]);
            $this->reset();
        }
        $this->redirect('/trial-balances');
    }

    public function previewSpreadsheet(){
        $path = $this->importedSpreadsheet->getRealPath();
        
        $this->spreadsheet = (new TrialBalanceImport)->toArray($path)[0];
        
        $this->preview["headers"] = array_slice($this->spreadsheet,5,1);
        $this->preview["data"] = array_slice($this->spreadsheet,6);
    }

    public function render()
    {
        if($this->importedSpreadsheet){
            $this->previewSpreadsheet();
        }

        return view('livewire.trial-balance.add-trial-balance');
    }
}
