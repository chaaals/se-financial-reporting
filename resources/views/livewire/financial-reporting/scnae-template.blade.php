<section class="h-full overflow-hidden overflow-y-scroll scrollbar rounded-t-lg">
    <table class="w-full">
        <thead>
            <th class="text-sm p-2 sticky top-0 text-white bg-primary">Account Titles</th>
            <th class="text-sm p-2 sticky top-0 text-white bg-primary">Amount</th>
        </thead>
        <tbody>
            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-4">Balance at January 1</td>
                <td>{{ $totalsData["Balance at January 1"] }}</td>
            </tr>
            {{-- Add Deduct --}}
            <tr>
                <td class="text-left font-bold pl-4">Add(Deduct):</td>
                <td>{{ '-' }}</td>
            </tr>
            <tr>
                <td class="text-left pl-6">Prior Period Errors</td>
                <td>{{ '-' }}</td>
            </tr>
            {{-- Add Deduct Changes in net assets --}}
            <tr>
                <td class="text-left font-bold pl-4">Add (Deduct) Changes in net assets/equity during the year</td>
                <td>{{ '-' }}</td>
            </tr>
            <tr>
                <td class="text-left pl-6">Adjustment of net Revenue recognized Directly in net assets/equity</td>
                <td>{{ '-' }}</td>
            </tr>
            <tr>
                <td class="text-left pl-6">Surplus for the period</td>
                <td>{{ $totalsData["Total Changes during the year"] }}</td>
            </tr>
            {{-- Total Changes --}}
            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-4">Total Changes during the year</td>
                <td>{{ '-' }}</td>
            </tr>
            <tr class="bg-slate-200">
                <td class="text-left font-bold pl-4">Balance at December 31</td>
                <td>{{ $totalsData["Balance at December 31"] }}</td>
            </tr>
        </tbody>
    </table>
</section>