<?php

namespace App\Livewire\FinancialReporting;

use Livewire\Component;

class FinancialPerformanceTemplate extends Component
{
    public $data;
    public $revenue;
    public $currOperatingExpense;

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
        $getTotalAmount = function (string $parent, $items, bool $verbose) {
            $amount = 0;

            if(count($items) > 0){
                foreach($items as $item){
                    foreach($this->accountTitles[$parent][$item] as $cell=>$title){
                        if($this->data[$cell]){
                            $amount += $this->data[$cell];
                        }
                    }            
                }
            } else {
                foreach($items as $cell=>$title){
                    if($this->data[$cell]){
                        $amount += $this->data[$cell];
                    }
                }
            }

            if($parent == 'revenue'){
                $this->revenue = $amount;
            }

            if($parent == 'currOperatingExpense'){
                $this->currOperatingExpense = $amount;
            }

            if($verbose){
                return $amount;
            }
        };
        return view('livewire.financial-reporting.financial-performance-template',
            ["data" => $this->data, "accountTitles" => $this->accountTitles, "getTotalAmount" => $getTotalAmount, "surplus" => $this->revenue - $this->currOperatingExpense]
        );
    }
}
