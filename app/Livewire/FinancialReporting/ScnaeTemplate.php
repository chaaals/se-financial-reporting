<?php

namespace App\Livewire\FinancialReporting;

use Livewire\Component;

class ScnaeTemplate extends Component
{
    public $data;
    public $totalsData;

    public function mount(string $data, string $totalsData){
        $this->data = json_decode($data, true);
        $this->totalsData = json_decode($totalsData, true);
    }
    public function render()
    {
        return view('livewire.financial-reporting.scnae-template',["data" => $this->data, "totalsData" => $this->totalsData]);
    }
}
