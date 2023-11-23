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

    #[Rule("nullable|sometimes|file|mimes:xlsx,xls")]
    public $imported_spreadsheet;
    public $spreadsheet = [];
    public $preview = [];

    public function add(){
        if($this->spreadsheet){
            TrialBalance::create([
                "tb_name" => "test tb",
                "period" => date("Y-m-d"),
                "tb_data" => json_encode($this->spreadsheet)
            ]);

            $this->reset();
        }
    }

    public function previewSpreadsheet(){
        $this->validate();
        $path = $this->imported_spreadsheet->getRealPath();
        
        $this->spreadsheet = (new TrialBalanceImport)->toArray($path)[0];
        
        $this->preview["headers"] = array_slice($this->spreadsheet,5,1);
        $this->preview["data"] = array_slice($this->spreadsheet,6);
    }

    public function render()
    {
        if($this->imported_spreadsheet){
            $this->previewSpreadsheet();
        }

        return view('livewire.trial-balance.add-trial-balance');
    }
}
