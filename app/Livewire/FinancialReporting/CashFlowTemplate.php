<?php

namespace App\Livewire\FinancialReporting;

use Livewire\Component;

class CashFlowTemplate extends Component
{
    public $data;
    public $totalsData;

    public $accountTitles = [
        "cashInflows" => [
            "13" => "Receipts from Business/Service Income",
            "15" => "Interest Income",
            "16" => "Other Receipts",
        ],
        "cashOutflows" => [
            "20" => "Payments to Suppliers and Creditors",
            "21" => "Payments to Officers and Employees",
        ],
        "cashOutflow" => [
            "29" => "Purchase of PPE",
        ],
        "others" => [
            "33" => "Total Cash Flows Provided by Opeartion",
            "34" => "Investing and Financing activities",
            "35" => "Add: Cash at the Beginning of the Year",
        ]

    ];

    public function mount(string $data, string $totalsData){
        $this->data = json_decode($data, true);
        $this->totalsData = json_decode($totalsData, true);
    }
    public function render()
    {

        return view('livewire.financial-reporting.cash-flow-template',
            ["data" => $this->data, "accountTitles" => $this->accountTitles, "totalsData" => $this->totalsData],
        );
    }
}
