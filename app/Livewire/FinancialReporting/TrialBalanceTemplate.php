<?php

namespace App\Livewire\FinancialReporting;

use Livewire\Component;

class TrialBalanceTemplate extends Component
{
    public $data;
    public $totalsData;
    public $accountTitles = [
        "assets" => [
            "cashOnHand" => [
                "1 01 01 010" => "Cash - Local Treasury",
                "1 01 01 020" => "Petty Cash",
            ],
            "cashInBankLocalCurrency" => [
                "1 01 02 010" => "Cash in Bank - Local Currency Current Account",
                "1 02 01 010" => "Cash in Bank - Local Currency Time Deposits"
            ],
            "financialAssets" => [
                "1 02 05 010" => "Guaranty Deposits"
            ],
            "loansAndReceivable" => [
                "1 03 01 010" => "Accounts Receivable",
                "1 03 01 070" => "Interests Receivable"
            ],
            "interAgencyReceivables" => [
                "1 03 03 010" => "Due from National Government Agencies",
                "1 03 03 030" => "Due from Local Government Units"
            ],
            "otherReceivables" => [
                "1 03 06 010" => "Receivables - Disallowances / Charges",
                "1 03 06 020" => "Due from Officers and Employees",
                "1 03 06 990" => "Other Receivables"
            ],
            "allowanceForImpairmentLoss" => ["1 03 01 011" => "Allowance for Impairment Loss"],
            "inventoryHeldForConsumption" => [
                "1 04 04 010" => "Office Supplies Inventory",
                "1 04 04 020" => "Accountable Forms, Plates and Stickers",
                "1 04 04 060" => "Drugs and Medicines Inventory",
                "1 04 04 070" => "Medical, Dental and Laboratory Supplies Inventory",
                "1 04 04 990" => "Other Supplies and Materials Inventory"
            ],
            "prepayments" => [
                "1 05 01 010" => "Advances to Contractors",
                "1 05 01 050" => "Prepaid Insurance"
            ],
            "buildingsAndOtherStructures" => [
                "1 07 04 020" => "School Buildings",
                "1 07 04 021" => "Accumulated Depreciation - School Buildings",
                "1 07 04 990" => "Other Structures",
                "1 07 04 991" => "Accumulated Depreciation - Other Structures",
            ],
            "machineryAndEquipment" => [
                "1 07 05 020" => "Office Equipment",
                "1 07 05 021" => "Accumulated Depreciation - Office Equipment",
                "1 07 05 030" => "Info and Communication Technology Equipment",
                "1 07 05 031" => "Accumulated Depreciation - ICT Equipment",
                "1 07 05 090" => "Disaster Response and Rescue Equipment",
                "1 07 05 091" => "Acc Depreciation - Disaster Response and Rescue Equipment",
                "1 07 05 100" => "Military, Police & Security Equipment",
                "1 07 05 101" => "Acc Depreciation - Military, Police & Security Equipment",
                "1 07 05 110" => "Medical Equipment",
                "1 07 05 111" => "Accumulated Depreciation - Medical Equipment",
                "1 07 05 130" => "Sports Equipment",
                "1 07 05 131" => "Accumulated Depreciation - Sports Equipment",
                "1 07 05 140" => "Technical and Scientific Equipment",
                "1 07 05 141" => "Acc Depreciation - Technical & Scientific Equipment",
                "1 07 05 990" => "Other Machinery & Equipment",
                "1 07 05 991" => "Acc Depreciation - Other Machinery & Equipment",
            ],
            "transportEquipment" => [
                "1 07 06 010" => "Motor Vehicles",
                "1 07 06 011" => "Accumulated Depreciation - Motor Vehicles"
            ],
            "furnitureFixturesAndBooks" => [
                "1 07 07 010" => "Furniture and Fixtures",
                "1 07 07 011" => "Accumulated Depreciation - Furniture and Fixture",
                "1 07 07 020" => "Books",
                "1 07 07 021" => "Accumulated Depreciation - Books",
            ],
            "otherPPE" => [
                "1 07 99 090" => "Disaster Response & Rescue Equipt",
                "1 07 99 990" => "Other Property, Plant and Equipment",
                "1 07 99 991" => "Acc Depreciation - Property, Plant and Equipment",
            ],
            "constructionInProgress" => [
                "1 07 10 020" => "Infrastructure Assets",
                "1 07 10 030" => "Buildings and Other Structures"
            ]
        ],

        "liabilities" => [
            "payables" => [
                "2 01 01 010" => "Accounts Payable",
                "2 01 01 020" => "Due to Officers and Employees"
            ],
            "interAgencyPayables" => [
                "2 02 01 010" => "Due to BIR",
                "2 02 01 020" => "Due to GSIS",
                "2 02 01 030" => "Due to PAG-IBIG",
                "2 02 01 040" => "Due to PHILHEALTH",
            ],
            "trustLiabilities" => [
                "2 04 01 010" => "Trust Liabilities",
                "2 04 01 040" => "Guaranty/Security Deposits Payable",
                "2 04 01 050" => "Customers' Deposit",
            ],
            "deferredCredits" => [
                "2 05 01 990" => "Other Deferred Credits"
            ],
            "otherPayables" => [
                "2 99 99 990" => "Other Payables"
            ]
        ],

        "equity" => [
            "governmentEquity" => [
                "3 01 01 010" => "Government Equity",
                "3 01 01 020" => "Prior Period Adjustment"
            ]
        ],

        "income" => [
            "serviceIncome" => [
                "4 02 01 980" => "Fines and Penalties - Service Income"
            ],
            "businessIncome" => [
                "4 02 02 010" => "School Fees",
                "4 02 02 020" => "Affiliation Fees",
                "4 02 02 050" => "Rent Income",
                "4 02 02 220" => "Interest Income",
                "4 02 02 990" => "Other Business Income",
            ],
            "assistanceAndSubsidy" => [
                "4 03 01 020" => "Subsidy from LGUs"
            ],
            "grantsAndDonations" => [
                "4 04 02 020" => "Grants & Donations in Kind"
            ],
            "miscIncome" => [
                "4 06 01 010" => "Miscellaneous Income"
            ]
        ],

        "personnelServices" => [
            "salariesAndWages" => [
                "5 01 01 010" => "Salaries and Wages - Regular",
                "5 01 01 020" => "Salaries and Wages - Casual/Contractual",
            ],
            "otherCompensation" => [
                "5 01 02 010" => "Personnel Economic Relief Allowance (PERA)",
                "5 01 02 020" => "Representation Allowance (RA)",
                "5 01 02 030" => "Transportation Allowance (TA)",
                "5 01 02 040" => "Clothing / Uniform Allowance",
                "5 01 02 100" => "Honoraria",
                "5 01 02 110" => "Hazard Pay",
                "5 01 02 120" => "Longevity Pay",
                "5 01 02 130" => "Overtime and Night Pay",
                "5 01 02 140" => "Year End Bonus",
                "5 01 02 150" => "Cash Gift",
            ],
            "personnelBenefitContributions" => [
                "5 01 03 010" => "Retirement and Life Insurance Premium",
                "5 01 03 020" => "Pag-ibig Contributions",
                "5 01 03 030" => "PhilHealth Contributions",
                "5 01 03 040" => "Employees Compensation Insurance Premiums",
            ],
            "otherPersonnelBenefits" => [
                "5 01 04 030" => "Terminal Leave Benefits",
                "5 01 04 990" => "Other Personnel Benefits",
            ]
        ],

        "maintenanceAndOtherOperatingExpenses" => [
            "travelingExpenses" => [
                "5 02 01 010" => "Travelling Expenses - Local"
            ],
            "trainingAndScholarshipExpenses" => [
                "5 02 02 010" => "Training Expenses"
            ],
            "suppliesAndMaterialsExpenses" => [
                "5 02 03 010" => "Office Supplies Expenses",
                "5 02 03 020" => "Accountable Forms Expenses",
                "5 02 03 070" => "Drugs and Medicines Expenses",
                "5 02 03 080" => "Medical,Dental and Laboratory Supplies Expenses",
                "5 02 03 090" => "Fuel,Oil and Lubricants Expenses",
                "5 02 03 990" => "Other Supplies and Materials Expenses",
            ],
            "utilityExpenses" => [
                "5 02 04 010" => "Water Expenses",
                "5 02 04 020" => "Electricity Expenses",
            ],
            "communicationExpenses" => [
                "5 02 05 010" => "Postage and Courier Services",
                "5 02 05 020" => "Telephone Expenses",
                "5 02 05 030" => "Internet Subscription Expenses",
            ],
            "intelligenceExpenses" => [
                "5 02 10 030" => "Extraordinary and Miscellaneous Expenses"
            ],
            "professionalServices" => [
                "5 02 11 990" => "Other Professional Services"
            ],
            "repairsAndMaintenance" => [
                "5 02 13 040" => "Repairs and Maint - Building & Other Structures",
                "5 02 13 050" => "Repairs and Maint - Machinery and Equipment",
                "5 02 13 060" => "Repairs and Maint - Transportation Equipment",
            ],
            "taxAndOtherFees" => [
                "5 02 16 020" => "Fidelity Bond Premiums",
                "5 02 16 030" => "Insurance Expenses",
            ],
            "otherMaintenanceAndOperatingExpenses" => [
                "5 02 99 020" => "Printing and Publication Expenses",
                "5 02 99 030" => "Representation Expenses",
                "5 02 99 050" => "Rent Expenses",
                "5 02 99 060" => "Membership Dues and Contribution to Org.",
                "5 02 99 070" => "Subscription Expenses",
                "5 02 99 990" => "Other Maintenance and Operating Expenses",
            ]
        ],

        "financialExpenses" => [
            "financialExpenses" => [
                "5 03 01 040" => "Bank Charges"
            ]
        ],

        "nonCashExpenses" => [
            "depreciation" => [
                "5 05 01 040" => "Depreciation - Building and Structures",
                "5 05 01 050" => "Depreciation - Machinery and Equipment",
                "5 05 01 060" => "Depreciation - Transportation Equipment",
                "5 05 01 070" => "Depreciation - Furnitures and Books",
            ]
        ]
    ];

    public function mount(string $data, string $totalsData){
        $this->data = json_decode($data, true);
        $this->totalsData = json_decode($totalsData, true);
    }

    public function render()
    {
        return view('livewire.financial-reporting.trial-balance-template');
    }
}
