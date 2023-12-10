<?php

namespace App\Livewire\FinancialReporting;

use Livewire\Component;

class CashFlowTemplate extends Component
{
    public $data;
    public $accountTitles = [

    ];

    public function mount(string $data){
        $this->data = json_decode($data, true);
    }
    public function render()
    {
        return view('livewire.financial-reporting.cash-flow-template',
            ["data" => $this->data, "accountTitles" => $this->accountTitles]
        );
    }
}
