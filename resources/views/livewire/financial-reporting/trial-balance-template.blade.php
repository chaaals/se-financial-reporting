<section class="h-full overflow-hidden overflow-y-scroll scrollbar rounded-t-lg">
    <table class="w-full">
        <thead>
            <th class="text-sm p-2 sticky top-0 text-white bg-primary">New Account Titles</th>
            <th class="text-sm p-2 sticky top-0 text-white bg-primary">Account Number</th>
            <th class="text-sm p-2 sticky top-0 text-white bg-primary">Debit</th>
            <th class="text-sm p-2 sticky top-0 text-white bg-primary">Credit</th>
        </thead>

        <tbody>
            {{-- assets --}}
            <x-financial-reporting.account-class accountClass="Assets" />
            {{-- cash --}}
            <x-financial-reporting.account-sub-class accountSubClass="Cash" />

            <x-financial-reporting.account-title accountTitle="Cash On Hand" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['assets']['cashOnHand']" :data="$data" />
           
            <x-financial-reporting.account-title accountTitle="Cash in Bank - Local Currency" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['assets']['cashInBankLocalCurrency']" :data="$data" />
            
            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-2">Total Cash</td>
                <td></td>
                <td>{{ $totalsData['Cash']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Cash']['credit'] ?? '-' }}</td>
            </tr>

            {{-- Financial Assets --}}
            <x-financial-reporting.account-sub-class accountSubClass="Financial Assets" />

            <x-financial-reporting.account-title accountTitle="Financial Assets - Others" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['assets']['financialAssets']" :data="$data" />
            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-2">Total Financial Assets</td>
                <td></td>
                <td>{{ $totalsData['Financial Assets']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Financial Assets']['credit'] ?? '-' }}</td>
            </tr>

            {{-- current receivables --}}
            <x-financial-reporting.account-sub-class accountSubClass="Current Receivables" />

            <x-financial-reporting.account-title accountTitle="Loans and Receivable Accounts" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['assets']['loansAndReceivable']" :data="$data" />
            
            <x-financial-reporting.account-title accountTitle="Inter-Agency Receivables" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['assets']['interAgencyReceivables']" :data="$data" />
            
            <x-financial-reporting.account-title accountTitle="Other Receivables" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['assets']['otherReceivables']" :data="$data" />
    
            <tr class="mb-4 bg-slate-200">
                <td class="text-left font-bold pl-2">Total Current Receivables</td>
                <td></td>
                <td>{{ $totalsData['Current Receivables']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Current Receivables']['credit'] ?? '-' }}</td>
            </tr>

            {{-- allowance impairment loss --}}
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['assets']['allowanceForImpairmentLoss']" :data="$data" />

            {{-- inventories --}}
            <x-financial-reporting.account-sub-class accountSubClass="Inventories" />

            <x-financial-reporting.account-title accountTitle="Inventory Held for Consumption" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['assets']['inventoryHeldForConsumption']" :data="$data" />
            
            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-2">Total Inventories</td>
                <td></td>
                <td>{{ $totalsData['Inventories']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Inventories']['credit'] ?? '-' }}</td>
            </tr>

            {{-- prepayments --}}
            <x-financial-reporting.account-sub-class accountSubClass="Prepayments and Deferred Charges" />
            
            <x-financial-reporting.account-title accountTitle="Prepayments" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['assets']['prepayments']" :data="$data" />
            
            <tr class="mb-4 bg-slate-200">
                <td class="text-left font-bold pl-2">Total Prepayments</td>
                <td></td>
                <td>{{ $totalsData['Prepayments']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Prepayments']['credit'] ?? '-' }}</td>
            </tr>

            {{-- ppe --}}
            <x-financial-reporting.account-sub-class accountSubClass="Property,Plant and Equipment" />
            
            <x-financial-reporting.account-title accountTitle="Buildings and Other Structures" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['assets']['buildingsAndOtherStructures']" :data="$data" />
            
            <x-financial-reporting.account-title accountTitle="Machinery and Equipment" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['assets']['machineryAndEquipment']" :data="$data" />

            <x-financial-reporting.account-title accountTitle="Transportation Equipment" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['assets']['transportEquipment']" :data="$data" />

            <x-financial-reporting.account-title accountTitle="Furniture,Fixtures and Books" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['assets']['furnitureFixturesAndBooks']" :data="$data" />

            <x-financial-reporting.account-title accountTitle="Other Property, Plant and Equipment" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['assets']['otherPPE']" :data="$data" />

            <x-financial-reporting.account-title accountTitle="Construction in Progress" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['assets']['constructionInProgress']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-2">Total Property,Plant and Equipment</td>
                <td></td>
                <td>{{ $totalsData['Property, Plant and Equipment']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Property, Plant and Equipment']['credit'] ?? '-' }}</td>
            </tr>
            <tr>
                <td class="text-left font-bold pl-2">Total Accumulated Depreciation</td>
                <td></td>
                <td>{{ $totalsData['Accumulated Depreciation']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Accumulated Depreciation']['credit'] ?? '-' }}</td>
            </tr>

            {{-- Liabilities --}}
            <x-financial-reporting.account-class accountClass="Liabilities" />

            {{-- financial liabilities --}}
            <x-financial-reporting.account-sub-class accountSubClass="Financial Liabilities" />

            <x-financial-reporting.account-title accountTitle="Payables" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['liabilities']['payables']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-2">Total Financial Liabilities</td>
                <td></td>
                <td>{{ $totalsData['Financial Liabilities']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Financial Liabilities']['credit'] ?? '-' }}</td>
            </tr>

            {{-- inter agency payables --}}
            <x-financial-reporting.account-sub-class accountSubClass="Inter - Agency Payables" />

            <x-financial-reporting.account-title accountTitle="Inter - Agency Payables" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['liabilities']['interAgencyPayables']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-2">Total Inter - Agency Payables</td>
                <td></td>
                <td>{{ $totalsData['Inter-Agency Payables']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Inter-Agency Payables']['credit'] ?? '-' }}</td>
            </tr>

            {{-- trust liabilities --}}
            <x-financial-reporting.account-sub-class accountSubClass="Trust Liabilities" />

            <x-financial-reporting.account-title accountTitle="Trust Liabilities" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['liabilities']['trustLiabilities']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-2">Total Trust Liabilities</td>
                <td></td>
                <td>{{ $totalsData['Trust Liabilities']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Trust Liabilities']['credit'] ?? '-' }}</td>
            </tr>

            {{-- deferred credits/unearned income --}}
            <x-financial-reporting.account-sub-class accountSubClass="Deferred Credits / Unearned Income" />

            <x-financial-reporting.account-title accountTitle="Deferred Credits" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['liabilities']['deferredCredits']" :data="$data" />

            {{-- other payables --}}
            <x-financial-reporting.account-title accountTitle="Other Payables" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['liabilities']['otherPayables']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold">Total Liabilities</td>
                <td></td>
                <td>{{ $totalsData['Liabilities']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Liabilities']['credit'] ?? '-' }}</td>
            </tr>

            {{-- equity --}}
            <x-financial-reporting.account-class accountClass="Equity" />

            <x-financial-reporting.account-sub-class accountSubClass="Government Equity" />

            <x-financial-reporting.account-title accountTitle="Government Equity" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['equity']['governmentEquity']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-2">Total Government Equity</td>
                <td></td>
                <td>{{ $totalsData['Government Equity']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Government Equity']['credit'] ?? '-' }}</td>
            </tr>

            {{-- income --}}
            <x-financial-reporting.account-class accountClass="Income" />

            <x-financial-reporting.account-sub-class accountSubClass="Service and Business Income" />

            <x-financial-reporting.account-title accountTitle="Service Income" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['income']['serviceIncome']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-2">Total Service Income</td>
                <td></td>
                <td>{{ $totalsData['Service Income']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Service Income']['credit'] ?? '-' }}</td>
            </tr>

            <x-financial-reporting.account-title accountTitle="Business Income" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['income']['businessIncome']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-2">Total Business Income</td>
                <td></td>
                <td>{{ $totalsData['Business Income']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Business Income']['credit'] ?? '-' }}</td>
            </tr>

            <x-financial-reporting.account-sub-class accountSubClass="Transfers,Assistance and Subsidy" />

            <x-financial-reporting.account-title accountTitle="Assistance and Subsidy" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['income']['assistanceAndSubsidy']" :data="$data" />

            <x-financial-reporting.account-sub-class accountSubClass="Shares,Grants & Donations" />

            <x-financial-reporting.account-title accountTitle="Grants & Donations" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['income']['grantsAndDonations']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-2">Total Grants & Donations</td>
                <td></td>
                <td>{{ $totalsData['Grants & Donations']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Grants & Donations']['credit'] ?? '-' }}</td>
            </tr>

            <x-financial-reporting.account-title accountTitle="Miscellaneous Income" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['income']['miscIncome']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold">Total Income</td>
                <td></td>
                <td>{{ $totalsData['Income']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Income']['credit'] ?? '-' }}</td>
            </tr>

            {{-- personnel services --}}
            <x-financial-reporting.account-sub-class accountSubClass="Personnel Services" />

            <x-financial-reporting.account-title accountTitle="Salaries and Wages" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['personnelServices']['salariesAndWages']" :data="$data" />

            <x-financial-reporting.account-title accountTitle="Other Compensation" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['personnelServices']['otherCompensation']" :data="$data" />

            <x-financial-reporting.account-title accountTitle="Personnel Benefit Contributions" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['personnelServices']['personnelBenefitContributions']" :data="$data" />

            <x-financial-reporting.account-title accountTitle="Other Personnel Benefits" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['personnelServices']['otherPersonnelBenefits']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-2">Total Personnel Services</td>
                <td></td>
                <td>{{ $totalsData['Personnel Services']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Personnel Services']['credit'] ?? '-' }}</td>
            </tr>

            {{-- maintenance and operating expenses --}}
            <x-financial-reporting.account-sub-class accountSubClass="Maintenance and Other Operating Expenses" />

            <x-financial-reporting.account-title accountTitle="Traveling Expenses" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['maintenanceAndOtherOperatingExpenses']['travelingExpenses']" :data="$data" />

            <x-financial-reporting.account-title accountTitle="Training and Scholarship Expenses" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['maintenanceAndOtherOperatingExpenses']['trainingAndScholarshipExpenses']" :data="$data" />

            <x-financial-reporting.account-title accountTitle="Supplies and Materials Expenses" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['maintenanceAndOtherOperatingExpenses']['suppliesAndMaterialsExpenses']" :data="$data" />

            <x-financial-reporting.account-title accountTitle="Utility Expenses" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['maintenanceAndOtherOperatingExpenses']['utilityExpenses']" :data="$data" />

            <x-financial-reporting.account-title accountTitle="Communication Expenses" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['maintenanceAndOtherOperatingExpenses']['communicationExpenses']" :data="$data" />

            <x-financial-reporting.account-title accountTitle="Confidential, Intelligence & Extraordinary Expenses" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['maintenanceAndOtherOperatingExpenses']['intelligenceExpenses']" :data="$data" />

            <x-financial-reporting.account-title accountTitle="Professional Services" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['maintenanceAndOtherOperatingExpenses']['professionalServices']" :data="$data" />

            <x-financial-reporting.account-title accountTitle="Repairs and Maintenance" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['maintenanceAndOtherOperatingExpenses']['repairsAndMaintenance']" :data="$data" />

            <x-financial-reporting.account-title accountTitle="Taxes, Insurance Premiums and Other Fees" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['maintenanceAndOtherOperatingExpenses']['taxAndOtherFees']" :data="$data" />

            <x-financial-reporting.account-title accountTitle="Other Maintenance and Operating Expenses" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['maintenanceAndOtherOperatingExpenses']['otherMaintenanceAndOperatingExpenses']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-4">
                    Total Other Maintenance and Operating Expenses
                </td>
                <td></td>
                <td>{{ $totalsData['Other Maintenance and Operating Expenses']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Other Maintenance and Operating Expenses']['credit'] ?? '-' }}</td>
            </tr>

            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-2">Total Maintenance and Other Operating Expenses</td>
                <td></td>
                <td>{{ $totalsData['Maintenance and Other Operating Expenses']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Maintenance and Other Operating Expenses']['credit'] ?? '-' }}</td>
            </tr>

            {{-- financial expenses --}}
            <x-financial-reporting.account-sub-class accountSubClass="Financial Expenses" />

            <x-financial-reporting.account-title accountTitle="Financial Expenses" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['financialExpenses']['financialExpenses']" :data="$data" />

            {{-- non cash --}}
            <x-financial-reporting.account-sub-class accountSubClass="Non Cash Expenses" />

            <x-financial-reporting.account-title accountTitle="Depreciation" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['nonCashExpenses']['depreciation']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-2">Total Non Cash</td>
                <td></td>
                <td>{{ $totalsData['Non Cash']['debit'] ?? '-' }}</td>
                <td>{{ $totalsData['Non Cash']['credit'] ?? '-' }}</td>
            </tr>
        </tbody>
    </table>
</section>
