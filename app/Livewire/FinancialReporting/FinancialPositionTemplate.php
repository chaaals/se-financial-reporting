<?php

namespace App\Livewire\FinancialReporting;

use Livewire\Component;

class FinancialPositionTemplate extends Component
{
    public $data;
    public $accountTitles = [
        "assets" => [
            "current" => [
                "17" => "Cash and Cash Equivalents",
                "18" => "Financial Assets",
                "19" => "Receivables",
                "20" => "Inventories",
                "21" => "Prepayments and Deferred Charges",
                "23" => "Less: Allowance for Impairment Loss"
            ],
            "nonCurrent" => [
                "32" => "Buildings and Other Structures",
                "33" => "Machinery and Equipment",
                "34" => "Transporation Equipment",
                "35" => "Furniture, Fixtures and Books",
                "36" => "Other Property, Plant & Equipment",
                "37" => "Construction in Progress",
            ]
        ],
        "liabilities" => [
            "current" => [
                "46" => "Financial Liabilities",
                "47" => "Inter - Agency Payables",
                "48" => "Trust Liabilities",
                "49" => "Deferred Credits/Unearned Income",
            ],
            "nonCurrent" => [
                "53" => "Other Payables"
            ]
        ],
        "equity" => [
            "61" => "Government Equity",
            "62" => "Surplus (Deficit) for the Period",
            "63" => "Prior Years' Adjustments",
            "64" => "Loss on Sale of Property, Plant and Equipment",
        ]
    ];

    public function mount(string $data){
        $this->data = json_decode($data, true);
    }
    public function render()
    {
        return view('livewire.financial-reporting.financial-position-template',
            ["data" => $this->data, "accountTitles" => $this->accountTitles]
        );
    }
}
