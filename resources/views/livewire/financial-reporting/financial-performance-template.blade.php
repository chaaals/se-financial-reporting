<section class="h-full overflow-hidden overflow-y-scroll scrollbar rounded-t-lg">
    <table class="w-full">
        <thead>
            <th class="text-sm p-2 sticky top-0 text-white bg-primary">Account Titles</th>
            <th class="text-sm p-2 sticky top-0 text-white bg-primary">Amount</th>
        </thead>
        <tbody>
            <x-financial-reporting.account-class accountClass="Revenue" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['revenue']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-4">Total Revenue</td>
                <td>{{ $getTotalAmount('revenue', [], true) }}</td>
            </tr>

            <x-financial-reporting.account-class accountClass="Less: Current Operating Expenses" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['currOperatingExpenses']" :data="$data" />

            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-4">Total Current Operating Expenses</td>
                <td>{{ $getTotalAmount('currOperatingExpenses', [], true) }}</td>
            </tr class="bg-slate-200">

            <tr>
                <td class="text-left font-bold">Surplus for the Period</td>
                <td>{{ $surplus }}</td>
            </tr>

            <tr class="bg-slate-200">
                <td class="text-left font-bold">Surplus (Deficit) for the Period</td>
                <td>{{ $surplus }}</td>
            </tr>

            <x-financial-reporting.account-class accountClass="Add(Less)" />
            <x-financial-reporting.account-title-items :accountTitles="$accountTitles['addLess']" :data="$data" />

            <tr>
                <td class="text-left pl-6">Loss on Sale of Property, PLant & Equipment</td>
                <td>{{ $getTotalAmount('addLess', [], true) }}</td>
            </tr>

            <tr class="bg-slate-200">
                <td class="text-left font-bold">Surplus (Deficit) for the Period</td>
                <td>{{ $surplus }}</td>
            </tr>
        </tbody>
    </table>
</section>