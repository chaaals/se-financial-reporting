<section class="h-full overflow-hidden overflow-y-scroll scrollbar rounded-t-lg">
    <table class="w-full">
        <thead>
            <th class="text-sm p-2 sticky top-0 text-white bg-primary">Account Titles</th>
            <th class="text-sm p-2 sticky top-0 text-white bg-primary">Amount</th>
        </thead>
        <tbody>
            <x-financial-reporting.account-class accountClass="Cash Flows from Operating Activities" />

            <x-financial-reporting.account-sub-class accountSubClass="Cash Inflows" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['cashInflows']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-4">Total Cash Inflows</td>
                <td>{{ $getTotalAmount('cashInflows', [], true) }}</td>
            </tr>

            <x-financial-reporting.account-sub-class accountSubClass="Cash Outflows" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['cashOutflows']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-4">Total Cash Outflows</td>
                <td>{{ $getTotalAmount('cashOutflows', [], true) }}</td>
            </tr>

            <tr class="bg-slate-200">
                <td class="text-left font-bold">Net Cash Flows from Operating Activities</td>
                <td>{{ $netCash }}</td>
            </tr>

            <x-financial-reporting.account-class accountClass="Cash Flows from Investing Activities" />

            <x-financial-reporting.account-sub-class accountSubClass="Cash Outflow" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['cashOutflow']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold">Net Cash Flows from Investing Activities</td>
                 <td>{{ $getTotalAmount('cashOutflow', [], true) }}</td>
            </tr>

            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['others']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold">Cash Balance at the End of the Quarter</td>
                <td>{{ $getCashBalanceEOQ() }}</td>
            </tr>
        </tbody>
    </table>
</section>