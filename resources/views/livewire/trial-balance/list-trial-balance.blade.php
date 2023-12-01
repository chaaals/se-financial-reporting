<section class="w-full p-4">

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
                @if($trial_balances)
                    @foreach($trial_balances as $index=>$tb)
                        <tr class={{ $index%2 == 0 ? 'bg-accentOne' : 'bg-white' }}>
                            <td class="p-2">
                                {{ $tb->report_name }}
                            </td>
                            <td class="hidden p-2 md:table-cell">
                                {{ $tb->tb_type ?? "Monthly Pre-closing" }}
                            </td>
                            <td class="hidden p-2 md:table-cell">
                                {{ $tb->interim_period }}
                            </td>
                            <td class="hidden p-2 md:table-cell">
                                {{ $tb->date }}
                            </td>
                            <td class="p-2 md:table-cell">
                                {{ $tb->report_status }}
                            </td>
                            <td class="flex items-center justify-center p-2">
                                <button>
                                    <x-financial-reporting.assets.trash-icon />
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>

            <tfoot>
                <tr>
                    <td class="flex items-center p-2">
                        <h4>Rows per page: </h4>
                        <select class="border-none active:border-none" wire:model='rows' wire:change='updatePage'>
                            <option value={{ 10 }}>10</option>
                            <option value={{ 15 }}>15</option>
                            <option value={{ 20 }}>20</option>
                        </select>
                    </td>
                    <td class="hidden md:table-cell"></td>
                    <td class="hidden md:table-cell"></td>
                    <td class="hidden md:table-cell"></td>
                    <td></td>
                    <td class="flex items-center justify-between p-4">
                        <button wire:click="previous">{{ "<" }}</button>
                        <button wire:click="next">{{ ">" }}</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </section>
</section>
