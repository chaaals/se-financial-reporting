<?php

namespace App\Livewire\TrialBalance;

use App\Imports\TrialBalanceImport;
use App\Models\TrialBalance;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddTrialBalance extends Component
{
    use WithFileUploads;

    public $tbName;
    public $period;
    public $isClosing = true; // default to true
    public $importedSpreadsheet;

    public $spreadsheet = [];
    public $preview = [];
    protected $rules = [
        "tbName" => "nullable|max:255",
        "period" => "required",
        "isClosing" => "nullable",
        "importedSpreadsheet" => "required|file|mimes:xlsx,xls",
    ];

    public function add(){
        $this->validate();
    
        if($this->spreadsheet){
            TrialBalance::create([
                "tb_name" => $this->tbName ?? "test tb",
                "period" => $this->period,
                "closing" => $this->isClosing,
                "tb_data" => json_encode($this->spreadsheet)
            ]);

            $this->reset();
        }
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
