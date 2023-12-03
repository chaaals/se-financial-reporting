<section class="w-full p-4">
    <section class="w-full flex items-center justify-between flex-col bg-white drop-shadow-md rounded-lg mb-4 p-2 md:flex-row 2xl:mb-8">
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
            
            <section class="flex items-center gap-4">
                <section class="flex items-center p-2 rounded-lg border-2 border-neutral gap-2">
                    <x-financial-reporting.assets.search />
                    <form wire:submit.prevent="search">
                        <input class="p-0 border-0 focus:ring-0 text-sm md:text-base" wire:model="searchInput" type="text" placeholder="Search..." />
                        <button type="submit" class="hidden"></button>
                    </form>
                </section>

                <button wire:click="create" class="bg-secondary text-white px-4 py-2 rounded-lg text-xs md:text-base">
                    Create Trial Balance
                </button>
            </section>
        </section>
    </section>

    <section class="bg-white drop-shadow-md rounded-lg">
        <section class="h-160 bg-white rounded-t-lg overflow-hidden overflow-y-scroll scrollbar sm:h-128 2xl:h-160">
            <table class="w-full">
                <thead>
                    <th class="w-36 bg-primary text-white relative text-left p-2 sticky top-0">
                        Name
                        <button wire:click="sort(0)" wire:target="sortBy" class="absolute top-1 right-2">
                            <x-financial-reporting.assets.table-sort />
                        </button>
                    </th>
                    <th class="w-36 bg-primary text-white relative text-left p-2 sticky top-0">
                        Date
                        <button wire:click="sort(1)" wire:target="sortBy" class="absolute top-1 right-2">
                            <x-financial-reporting.assets.table-sort />
                        </button>
                    </th>
                    <th class="w-36 hidden bg-primary text-white relative text-left p-2 sticky top-0 md:table-cell">
                        Period
                        <button wire:click="sort(2)" wire:target="sortBy" class="absolute top-1 right-2">
                            <x-financial-reporting.assets.table-sort />
                        </button>
                    </th>
                    <th class="w-24 hidden bg-primary text-white relative text-left p-2 sticky top-0 md:table-cell">
                        Quarter
                        <button wire:click="sort(3)" wire:target="sortBy" class="absolute top-1 right-2">
                            <x-financial-reporting.assets.table-sort />
                        </button>
                    </th>
                    <th class="w-40 hidden bg-primary text-white relative text-left p-2 sticky top-0 md:table-cell">
                        Created At
                        <button wire:click='sort(4)' wire:target="sortBy" class="absolute top-1 right-2">
                            <x-financial-reporting.assets.table-sort />
                        </button>
                    </th>
                    <th class="w-40 hidden bg-primary text-white relative text-left p-2 sticky top-0 md:table-cell">
                        Updated At
                        <button wire:click="sort(5)" wire:target="sortBy" class="absolute top-1 right-2">
                            <x-financial-reporting.assets.table-sort />
                        </button>
                    </th>
                    <th class="w-24 hidden bg-primary text-white relative text-left p-2 sticky top-0 md:table-cell">
                        Status
                        <button wire:click="sort(6)" wire:target="sortBy" class="absolute top-1 right-2">
                            <x-financial-reporting.assets.table-sort />
                        </button>
                    </th>

                    <th class="w-20 bg-primary text-white relative text-left p-2 sticky top-0"></th>
                </thead>

                <tbody>
                    @if($trial_balances)
                        @foreach($trial_balances as $index=>$tb)
                            <tr class={{ $index%2 == 0 ? 'bg-accentOne' : 'bg-white' }}>
                                <td class="h-16 p-2 text-center whitespace-wrap">
                                    <a href="/trial-balances/{{ $tb->tb_id }}">{{ $tb->report_name }}</a>
                                </td>
                                <td class="h-16 p-2 text-center whitespace-nowrap">
                                    {{ date('M d, Y', strtotime($tb->date)) }}
                                </td>
                                <td class="h-16 p-2 hidden text-center whitespace-nowrap md:table-cell">
                                    {{ $tb->interim_period }}
                                </td>
                                <td class="h-16 p-2 hidden text-center whitespace-nowrap md:table-cell">
                                    {{ $tb->quarter ?? "-" }}
                                </td>
                                <td class="h-16 p-2 hidden text-center whitespace-wrap md:table-cell">
                                    {{ date('M d, Y H:i:s', strtotime($tb->created_at)) }}
                                </td>
                                <td class="h-16 p-2 hidden text-center whitespace-wrap md:table-cell">
                                    {{ date('M d, Y H:i:s', strtotime($tb->updated_at)) }}
                                </td>
                                <td class="h-16 p-2 hidden text-center whitespace-nowrap md:table-cell">
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
                <h4 class="text-sm md:text-base">Rows per page: </h4>
                <select class="border-none active:border-none text-sm md:text-base" wire:model='rows' wire:change='updatePage'>
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
</section>
