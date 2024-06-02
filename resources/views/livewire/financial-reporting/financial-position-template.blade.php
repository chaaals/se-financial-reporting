<section class="h-full overflow-hidden overflow-y-scroll scrollbar rounded-t-lg">
    <table class="w-full">
        <thead>
            <th class="text-sm p-2 text-white sticky top-0 bg-primary">Account Titles</th>
            <th class="text-sm p-2 text-white sticky top-0 bg-primary">Amount</th>
        </thead>
        <tbody>
            <x-financial-reporting.account-class accountClass="Assets" />

            <x-financial-reporting.account-title accountTitle="Current Assets" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['assets']['current']" :data="$data" />
            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-4">Total Current Assets</td>
                <td>{{ $totalsData['Current Assets'] }}</td>
            </tr>

            <x-financial-reporting.account-title accountTitle="Non-Current Assets" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['assets']['nonCurrent']" :data="$data" />
            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-4">Total Non-Current Assets</td>
               <td>{{ $totalsData['Non-Current Assets'] }}</td>
            </tr>

            <tr class="bg-slate-200">
                <td class="text-left font-bold">Total Assets</td>
                <td>{{ $totalsData['Assets'] }}</td>
            </tr>

            <x-financial-reporting.account-class accountClass="Liabilities" />

            <x-financial-reporting.account-title accountTitle="Current Liabilities" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['liabilities']['current']" :data="$data" />
            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-4">Total Current Liabilities</td>
                <td>{{ $totalsData['Current Liabilities'] }}</td>
            </tr>

            <x-financial-reporting.account-title accountTitle="Non-Current Liabilities" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['liabilities']['nonCurrent']" :data="$data" />
            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-4">Total Non-Current Liabilities</td>
                <td>{{ $totalsData['Non-Current Liabilities'] }}</td>
            </tr>
            <tr class="bg-slate-200">
                <td class="text-left font-bold">Total Liabilities</td>
                <td>{{ $totalsData['Liabilities'] }}</td>
            </tr>

            <x-financial-reporting.account-class accountClass="Equity" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['equity']" :data="$data" />
            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-4">Total Equity</td>
                <td>{{ $totalsData['Equity'] }}</td>
            </tr>

            <tr class="bg-slate-200">
                <td class="text-left font-bold">Total Liabilities and Equity</td>
                <td>{{ $totalsData['Liabilities and Equity'] }}</td>
            </tr>
        </tbody>
    </table>
</section>