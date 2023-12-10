<?php

namespace App\Livewire\FinancialReporting;

use Livewire\Component;

class FinancialPerformanceTemplate extends Component
{
    public $data;
    public $accountTitles = [
        "revenue" => [
            "14" => "Service Income",
            "15" => "Business Income",
            "16" => "Shares, Grants and Donations",
            "17" => "Other Income",
        ],
        "currOperatingExpenses" => [
            "22" => "Personnel Services",
            "23" => "Maintenance & Other Operating Expenses",
            "24" => "Non-Cash Expenses (Depreciation)",
            "25" => "Financial Expenses",
            "26" => "Loss on Sale of Property, Plant & Equipment",
            "27" => "Impairment Loss",
        ],
        "addLess" => [
            "35" => "Add: Subsidy from LGU",
            "36" => "Bank Charges",
        ]
    ];

    public function mount(string $data){
        $this->data = json_decode($data, true);
    }
    public function render()
    {
        return view('livewire.financial-reporting.financial-performance-template',
            ["data" => $this->data, "accountTitles" => $this->accountTitles]
        );
    }
}
