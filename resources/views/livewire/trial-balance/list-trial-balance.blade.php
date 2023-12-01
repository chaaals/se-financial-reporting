<section class="w-full p-4">
    {{-- @if($trial_balances)
    <section>
        @foreach($trial_balances as $tb)
            <a href="/trial-balances/{{ $tb->tb_id }}">{{ $tb->report_name }}</a>
            @if ($tb->tb_type)
                <div>Type: {{ $tb->tb_type }}</div>
            @endif
            <div>Status: {{ $tb->report_status }}</div>
            <div>Date: {{ $tb->date }}</div>
            @if ($tb->quarter)
                <div>Quarter: {{ $tb->quarter }}</div>
            @endif
            <div>Interim Period: {{ $tb->interim_period }}</div>

            <div>
                <!-- delete -->
                <button wire:click="confirmDelete('{{ $tb->tb_id }}')">Delete</button>
                <!-- confirm deletion -->
                @if ($confirming === $tb->tb_id)
                    <button wire:click="deleteTrialBalance('{{ $tb->tb_id }}')">Confirm Delete</button>
                    <button wire:click="$set('confirming', null)">Cancel</button>
                @endif
            </div>
        @endforeach
    </section>
    @endif --}}
{{-- border-2 border-solid border-indigo-500/50 --}}
    <section class="bg-white drop-shadow-md rounded-lg">
        <table class="w-full table-auto rounded-t-md overflow-hidden">
            <thead class="bg-primary text-white">
                <th class="relative text-left p-2">
                    Report name
                    <button class="absolute top-4 right-4 md:top-1 md:right-2">
                        <x-financial-reporting.assets.table-sort />
                    </button>
                </th>
                <th class="relative hidden md:table-cell md:text-left p-2">
                    Report type
                    <button class="absolute top-1 right-2">
                        <x-financial-reporting.assets.table-sort />
                    </button>
                </th>
                <th class="relative hidden text-left md:table-cell p-2">
                    Interim period
                    <button class="absolute top-1 right-2">
                        <x-financial-reporting.assets.table-sort />
                    </button>
                </th>
                <th class="relative hidden text-left md:table-cell p-2">
                    Date
                    <button class="absolute top-1 right-2">
                        <x-financial-reporting.assets.table-sort />
                    </button>
                </th>
                <th class="relative text-left p-2">
                    Report status
                    <button class="absolute top-4 right-4 md:top-1 md:right-2">
                        <x-financial-reporting.assets.table-sort />
                    </button>
                </th>
                <th class="relative text-left p-2">
                    Actions
                </th>
            </thead>

            <tbody>
                <tr class="bg-accentOne">
                    <td class="p-2">Trial Balance report 1</td>
                    <td class="p-2">Pre-closing</td>
                    <td class="p-2">Monthly</td>
                    <td class="p-2">{{ date("M-d-Y") }}</td>
                    <td class="p-2">Draft</td>
                    <td class="p-2"></td>
                </tr>
            </tbody>

            <tfoot>
                <tr>
                    <td class="flex items-center">
                        <h4>Rows per page</h4>
                        <select>
                            <option>10</option>
                            <option>15</option>
                            <option>20</option>
                        </select>
                    </td>
                    <td class="hidden md:table-cell"></td>
                    <td class="hidden md:table-cell"></td>
                    <td class="hidden md:table-cell"></td>
                    <td></td>
                    <td class="flex items-center justify-between p-4">
                        <button>{{ "<" }}</button>
                        <button>{{ ">" }}</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </section>
</section>
