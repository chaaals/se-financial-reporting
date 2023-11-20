<?php

namespace App\Livewire\TrialBalance;

use App\Imports\TrialBalanceImport;
use App\Models\TrialBalance;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class AddTrialBalance extends Component
{
    use WithFileUploads;

    #[Rule("nullable|sometimes|file|mimes:xlsx,xls")]
    public $imported_spreadsheet;
    public $spreadsheet = [];

    public function add(){
        if($this->spreadsheet){
            $tb_data = [];

            foreach($this->spreadsheet["data"] as $rows){
                if($rows[4]){
                    $tb_data[$rows[4]] = [
                        "debit" => $rows[5],
                        "credit" => $rows[7]
                    ];
                }
            }

            TrialBalance::create([
                "tb_name" => "test tb",
                "period" => date("Y-m-d"),
                "tb_data" => json_encode($tb_data)
            ]);

            $this->reset();
        }
    }

    public function previewSpreadsheet(){
        $this->validate();
        $path = $this->imported_spreadsheet->getRealPath();
        
        $spreadsheet = Excel::toArray(new TrialBalanceImport,$path)[0];

        $this->spreadsheet["headers"] = array_slice($spreadsheet,5,1);
        $this->spreadsheet["data"] = array_slice($spreadsheet,6);
    }

    public function render()
    {
        if($this->imported_spreadsheet){
            $this->previewSpreadsheet();
        }

        return view('livewire.trial-balance.add-trial-balance');
    }
}
