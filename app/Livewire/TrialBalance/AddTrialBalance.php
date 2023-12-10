<?php

namespace App\Livewire\TrialBalance;

use App\Imports\TrialBalanceImport;
use App\Models\TrialBalance;
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
    public $date;
    public $interimPeriod;
    public $quarter;
    
    public $source;
    public $importedSpreadsheet;

    public $spreadsheet = [];
    public $preview = [];
    protected $rules = [
        "tbName" => "nullable|max:255",
        "date" => "required|date",
        "tbType" => "nullable|in:pre,post",
        "importedSpreadsheet" => "required|file|mimes:xlsx,xls,ods",
        'interimPeriod' => 'required|in:Monthly,Quarterly,Annual',
        'quarter' => 'nullable',
    ];

    public function mount()
    {
        // default values so user does not need to interact with the form and just save
        $this->date = date('Y-m-d');

        $formattedDate = date('M d, Y',strtotime($this->date));
        $this->tbName = "Trial Balance Report as of $formattedDate";
    }

    public function add(){
        $fr_month = date('m', strtotime($this->date));

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
        if($this->importedSpreadsheet && $this->tbData){
            TrialBalance::create([
                "tb_name" => $this->tbName,
                "tb_type" => $this->tbType ?? null,
                "tb_status" => 'Draft',
                "tb_data" => $this->tbData,
                "interim_period" => $this->interimPeriod,
                "quarter" => $this->quarter,
                "approved" => false,
                "date" => $this->date,
                "template_name" => 'tb_pre'
            ]);
            $this->reset();
        }
        $this->redirect('/trial-balances');
    }

    private function getTBData() {
        $tbImportConfig = DB::select("SELECT template FROM report_templates WHERE template_name = 'tb_pre'");
        $jsonConfig = array_column($tbImportConfig, 'template')[0];
        $jsonConfig = json_decode($jsonConfig, true);
        $tbData = $jsonConfig;
        
        $spreadsheet = IOFactory::load($this->importedSpreadsheet->getRealPath());
        
        foreach ($jsonConfig as $accountCode => $row) {
            $debit = $spreadsheet->getActiveSheet()->getCell("F".$row)->getValue();
            $credit = $spreadsheet->getActiveSheet()->getCell("H".$row)->getValue();
            $tbData[$accountCode] = [
                "debit" => $debit,
                "credit" => $credit
            ];
        }
        
        return json_encode($tbData);
    }

    public function resetImport(){
        if($this->tbData && $this->importedSpreadsheet){
            $this->reset(['tbData', 'importedSpreadsheet']);
        }
    }
    public function cancel(){
        return $this->redirect('/trial-balances', navigate: true);
    }

    public function render()
    {
        if($this->importedSpreadsheet){
            $this->tbData = $this->getTBData();
        }

        return view('livewire.trial-balance.add-trial-balance');
    }
}
