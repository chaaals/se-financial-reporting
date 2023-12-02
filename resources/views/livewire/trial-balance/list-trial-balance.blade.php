<section class="w-full p-4">
    <section class="w-full flex items-center justify-between flex-col bg-white drop-shadow-md rounded-lg mb-8 p-2 md:flex-row">
        <h1 class="text-primary text-header font-bold font-inter">Trial Balances</h1>

        <section class="flex items-center flex-col gap-4 md:flex-row">
            <section class="flex items-center gap-4">
            @foreach($filterOptions as $key=>$filter)
               <select 
                    class="w-20 text-xs appearance-none rounded-lg border-neutral pr-8 md:w-24 md:text-sm"
                    @if (in_array($key, ['Quarter']) && !in_array($filterPeriod, ['Quarterly']))
                        disabled
                    @endif
                    wire:model={{ $filter['model'] }}
                    wire:change="updatePage"
                >
                        <option selected hidden>{{ $key }}</option>
                    @foreach($filter['options'] as $option)
                        <option value='{{ $option }}'>{{ $option }}</option>
                    @endforeach
               </select>
            @endforeach

                <button wire:click='refreshFilters'>
                    <x-financial-reporting.assets.refresh />
                </button>
            </section>
            
            <section>
                <button wire:click="create" class="bg-secondary text-white px-4 py-2 rounded-lg text-xs md:text-base">
                    Create Trial Balance
                </button>
            </section>
        </section>
    </section>

    <section class="hidden bg-white drop-shadow-md rounded-lg md:block">
        <section class="h-160 bg-white rounded-t-lg overflow-hidden overflow-y-scroll scrollbar">
            <table class="w-full">
                <thead>
                    <th class="w-36 bg-primary text-white relative text-left p-2 sticky top-0">
                        Name
                        <button class="absolute top-4 right-4 md:top-1 md:right-2">
                            <x-financial-reporting.assets.table-sort />
                        </button>
                    </th>
                    <th class="w-36 bg-primary text-white relative text-left p-2 sticky top-0">
                        Date
                        <button class="absolute top-1 right-2">
                            <x-financial-reporting.assets.table-sort />
                        </button>
                    </th>
                    <th class="w-36 bg-primary text-white relative text-left p-2 sticky top-0">
                        Period
                        <button class="absolute top-1 right-2">
                            <x-financial-reporting.assets.table-sort />
                        </button>
                    </th>
                    <th class="w-24 bg-primary text-white relative text-left p-2 sticky top-0">
                        Quarter
                        <button class="absolute top-1 right-2">
                            <x-financial-reporting.assets.table-sort />
                        </button>
                    </th>
                    <th class="w-40 bg-primary text-white relative text-left p-2 sticky top-0">
                        Created At
                        <button class="absolute top-4 right-4 md:top-1 md:right-2">
                            <x-financial-reporting.assets.table-sort />
                        </button>
                    </th>
                    <th class="w-40 bg-primary text-white relative text-left p-2 sticky top-0">
                        Updated At
                        <button class="absolute top-4 right-4 md:top-1 md:right-2">
                            <x-financial-reporting.assets.table-sort />
                        </button>
                    </th>
                    <th class="w-24 bg-primary text-white relative text-left p-2 sticky top-0">
                        Status
                        <button class="absolute top-4 right-4 md:top-1 md:right-2">
                            <x-financial-reporting.assets.table-sort />
                        </button>
                    </th>

                    <th class="w-20 bg-primary text-white relative text-left p-2 sticky top-0"></th>
                </thead>

                <tbody>
                    @if($trial_balances)
                        @foreach($trial_balances as $index=>$tb)
                            <tr class={{ $index%2 == 0 ? 'bg-accentOne' : 'bg-white' }}>
                                <td class="h-16 p-2 text-center whitespace-nowrap">
                                    {{ $tb->report_name }}
                                </td>
                                <td class="h-16 p-2 text-center whitespace-nowrap">
                                    {{ date('M d, Y', strtotime($tb->date)) }}
                                </td>
                                <td class="h-16 p-2 text-center whitespace-nowrap">
                                    {{ $tb->interim_period }}
                                </td>
                                <td class="h-16 p-2 text-center whitespace-nowrap">
                                    {{ $tb->quarter ?? "-" }}
                                </td>
                                <td class="h-16 p-2 text-center whitespace-wrap">
                                    {{ date('M d, Y H:i:s', strtotime($tb->created_at)) }}
                                </td>
                                <td class="h-16 p-2 text-center whitespace-wrap">
                                    {{ date('M d, Y H:i:s', strtotime($tb->updated_at)) }}
                                </td>
                                <td class="h-16 p-2 text-center whitespace-nowrap">
                                    {{ $tb->report_status }}
                                </td>
                                <td class="h-16 p-2">
                                    <div class="flex items-center justify-center">
                                        <button class="h-full items-center">
                                            <x-financial-reporting.assets.trash-icon />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

        </section>
        <section class="flex items-center justify-between px-4">
            <div class="flex items-center gap-2">
                <h4>Rows per page: </h4>
                <select class="border-none active:border-none" wire:model='rows' wire:change='updatePage'>
                    <option value={{ 10 }}>10</option>
                    <option value={{ 15 }}>15</option>
                    <option value={{ 20 }}>20</option>
                </select>
            </div>
            <div class="w-32 flex items-center justify-between p-4">
                <button wire:click="previous">
                    <x-financial-reporting.assets.arrow-left />
                </button>
                <button wire:click="next">
                    <x-financial-reporting.assets.arrow-right />
                </button>
            </div>
        </section>
    </section>

    <section class="md:hidden">
        @if($trial_balances)
            @foreach($trial_balances as $tb)
                <section class="border-2 border-solid border-black">
                    <div>{{ $tb->report_name }}</div>
                    <div>{{ date('M d, Y', strtotime($tb->date)) }}</div>
                    <div>{{ $tb->interim_period }}</div>
                    <div>{{ $tb->quarter ?? "-" }}</div>
                    <div>{{ date('M d, Y H:i:s', strtotime($tb->created_at)) }}</div>
                    <div>{{ date('M d, Y H:i:s', strtotime($tb->updated_at)) }}</div>
                    <div>{{ $tb->report_status }}</div>
                </section>
            @endforeach
        @endif
    </section>
</section>
