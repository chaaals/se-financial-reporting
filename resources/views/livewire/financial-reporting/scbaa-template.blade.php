<section class="h-full overflow-hidden overflow-y-scroll scrollbar rounded-t-lg">
    <table class="w-full">
        <thead>
            <th class="text-xs p-2 sticky top-0 text-white bg-primary">Particulars</th>
            <th class="text-xs p-2 sticky top-0 text-white bg-primary">Original</th>
            <th class="text-xs p-2 sticky top-0 text-white bg-primary">Final</th>
            <th class="text-xs p-2 sticky top-0 text-white bg-primary">Original and Final Budget</th>
            <th class="text-xs p-2 sticky top-0 text-white bg-primary">Amounts</th>
            <th class="text-xs p-2 sticky top-0 text-white bg-primary">Final Budget and Actual</th>
        </thead>
        <tbody>
            <tr>
                <td class="text-left text-sm font-bold">Revenue</td>
            </tr>

            {{-- Local Sources --}}
            <tr>
                <td class="text-left text-sm font-bold pl-2">A. Local Sources</td>
            </tr>
            <tr>
                <td class="text-left text-sm font-bold pl-4">1. Non-Tax Revenue</td>
            </tr>
            <tr>
                <td class="text-left text-sm pl-6">a. Service Income</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ $data["17"] }}</td>
                <td>{{ $data["17"] }}</td>
            </tr>
            <tr>
                <td class="text-left text-sm pl-6">b. Business Income</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ $data["18"] }}</td>
                <td>{{ $data["18"] }}</td>
            </tr>
            <tr>
                <td class="text-left text-sm pl-6">c. Other Income</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ $data["19"] }}</td>
                <td>{{ $data["19"] }}</td>
            </tr>
            <tr class="bg-slate-200">
                <td class="text-left text-sm font-bold pl-4">Total Non-Tax Revenue</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ $totalsData["Non-Tax Revenue"] }}</td>
                <td>{{ $totalsData["Non-Tax Revenue"] }}</td>
            </tr>
            {{-- External Resources --}}
            <tr>
                <td class="text-left text-sm font-bold pl-2">B. External Sources</td>
            </tr>
            <tr>
                <td class="text-left text-sm font-bold pl-4">1. Other Receipts</td>
            </tr>
            <tr>
                <td class="text-left text-sm pl-6">a. Grant and Donations</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ $data["23"] }}</td>
                <td>{{ $data["23"] }}</td>
            </tr>
            <tr>
                <td class="text-left text-sm pl-6">b. Other Subsidy Income</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ $data["24"] }}</td>
                <td>{{ $data["24"] }}</td>
            </tr>
            <tr class="bg-slate-200">
                <td class="text-left text-sm font-bold">Total Revenue and Receipts</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ $totalsData["Revenue and Receipts"] }}</td>
                <td>{{ $totalsData["Revenue and Receipts"] }}</td>
            </tr>
            {{-- Expenditures --}}
            <tr>
                <td class="text-left text-sm font-bold">Revenue</td>
            </tr>
            <tr>
                <td class="text-left text-sm font-bold">Current Appropriations</td>
            </tr>
            <tr>
                <td class="text-left text-sm font-bold pl-2">Education</td>
            </tr>
            <tr>
                <td class="text-left text-sm pl-4">Personal Services</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ $data["29"] }}</td>
                <td>{{ $data["29"] }}</td>
            </tr>
            <tr>
                <td class="text-left text-sm pl-4">Maintenance and Other Operating Expenses</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ $data["30"] }}</td>
                <td>{{ $data["30"] }}</td>
            </tr>
            <tr>
                <td class="text-left text-sm pl-4">Capital Outlay</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
            </tr>
            
            <tr>
                <td class="text-left text-sm font-bold pl-2">Other Purposes:</td>
            </tr>
            <tr>
                <td class="text-left text-sm pl-2">Debit Service</td>
            </tr>
            <tr>
                <td class="text-left text-sm pl-4">Financial Expense</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
            </tr>
            <tr class="bg-slate-200">
                <td class="text-left text-sm font-bold">Total Revenue and Receipts</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ $totalsData["Current Appropriations"] }}</td>
                <td>{{ $totalsData["Current Appropriations"] }}</td>
            </tr>

            <tr>
                <td class="text-left text-sm font-bold">Continuing Appropriations</td>
            </tr>
            <tr>
                <td class="text-left text-sm font-bold pl-2">Education</td>
            </tr>
            <tr>
                <td class="text-left text-sm pl-4">Capital Outlay</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
            </tr>

            <tr class="bg-slate-200">
                <td class="text-left text-sm font-bold pl-2">Total Continuing Appropriations</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ $totalsData["Continuing Appropriations"] }}</td>
                <td>{{ $totalsData["Continuing Appropriations"] }}</td>
            </tr>

            <tr class="bg-slate-200">
                <td class="text-left text-sm font-bold">Total Appropriations</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ $totalsData["Appropriations"] }}</td>
                <td>{{ $totalsData["Appropriations"] }}</td>
            </tr>

            <tr>
                <td class="text-left text-sm font-bold">Surplus (Deficit) for the period</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ $data["41"] }}</td>
                <td>{{ $data["41"] }}</td>
            </tr>
        </tbody>
    </table>
</section>