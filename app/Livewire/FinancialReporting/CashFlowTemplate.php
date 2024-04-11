<?php

namespace App\Livewire\FinancialReporting;

use Livewire\Component;

class CashFlowTemplate extends Component
{
    public $data;
    public $outflow;
    public $inflow;

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
            
            if($parent == 'cashInflows'){
                $this->inflow = $amount;
            }

            if($parent == 'cashOutflows'){
                $this->outflow = $amount;
            }

            if($verbose){
                return $amount;
            }
        };

        $getCashBalanceEOQ = function () {
            return $this->data['34'] + $this->data['35'];
        };

        return view('livewire.financial-reporting.cash-flow-template',
            ["data" => $this->data, "accountTitles" => $this->accountTitles, "getTotalAmount" => $getTotalAmount, "getCashBalanceEOQ" => $getCashBalanceEOQ ,"netCash" => $this->inflow - $this->outflow],
        );
    }
}
