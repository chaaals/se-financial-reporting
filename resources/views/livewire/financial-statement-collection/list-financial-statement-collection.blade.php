<section x-data="{ isActionModalOpen: false }" class="w-full p-4">
    <section class="w-full flex items-center justify-between flex-col bg-white drop-shadow-md rounded-lg mb-4 p-2 md:flex-row 2xl:mb-8">
        <h1 class="text-primary text-header font-bold font-inter">Financial Statements</h1>

        <section class="flex items-center flex-col gap-2 md:flex-row">
            <section class="flex items-center gap-2">
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
            
            <section class="flex items-center gap-2">
                <section class="flex items-center p-2 rounded-lg border-2 border-neutral gap-2">
                    <x-financial-reporting.assets.search />
                    <form wire:submit.prevent="search">
                        <input class="p-0 border-0 focus:ring-0 text-sm md:text-base" wire:model="searchInput" type="text" placeholder="Search..." />
                        <button type="submit" class="hidden"></button>
                    </form>
                </section>

                <button wire:click="create" class="bg-secondary text-white px-4 py-2 rounded-lg text-xs md:text-base">
                    Create Statements
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
                    @if($fs_collection)
                        @foreach($fs_collection as $index=>$fsc)
                            <tr class={{ $index%2 == 0 ? 'bg-accentOne' : 'bg-white' }}>
                                <td
                                    class="h-16 p-2 text-center whitespace-wrap cursor-pointer hover:text-secondary"
                                    wire:click="preview('{{$fsc->collection_id}}')">
                                    {{ $fsc->collection_name }}
                                </td>
                                <td class="h-16 p-2 text-center whitespace-nowrap">
                                    {{ date('M d, Y', strtotime($fsc->date)) }}
                                </td>
                                <td class="h-16 p-2 hidden text-center whitespace-nowrap md:table-cell">
                                    {{ $fsc->interim_period }}
                                </td>
                                <td class="h-16 p-2 hidden text-center whitespace-nowrap md:table-cell">
                                    {{ $fsc->quarter ?? "-" }}
                                </td>
                                <td class="h-16 p-2 hidden text-center whitespace-wrap md:table-cell">
                                    {{ date('M d, Y H:i:s', strtotime($fsc->created_at)) }}
                                </td>
                                <td class="h-16 p-2 hidden text-center whitespace-wrap md:table-cell">
                                    {{ date('M d, Y H:i:s', strtotime($fsc->updated_at)) }}
                                </td>
                                <td class="h-16 p-2 hidden text-center whitespace-nowrap md:table-cell">
                                    {{ $fsc->collection_status }}
                                </td>
                                <td class="h-16 p-2">
                                    <div class="flex items-center justify-center">
                                        <button 
                                        x-on:click="isActionModalOpen = true"
                                        wire:click="setFSCollection({{$index}})"
                                        class="h-full items-center">
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

    {{-- Modal --}}
    @if($fsCollection)
    <div
        x-cloak
        x-show="isActionModalOpen"
        role="dialog"
        class="fixed top-0 left-0 w-screen h-screen bg-neutral bg-opacity-50 flex items-center justify-center">
        <div class="w-80 bg-white drop-shadow-md p-4 rounded-lg">
            <h1 class="text-2xl font-bold font-inter mb-2">Delete Record</h1>
            <p class="whitespace-normal font-inter text-sm mb-4">
                Are you sure you want to delete <strong>{{$fsCollection->collection_name}}</strong>? This will remove the record and can't be undone.
            </p>
            <div class="w-full flex justify-between items-center">
                <button class="text-white bg-neutral rounded-lg font-inter w-20 p-2" type="button" x-on:click="isActionModalOpen = false" wire:click="setFSCollection">Cancel</button>
                <button class="text-white bg-accentTwo rounded-lg font-inter w-20 p-2" type="button" x-on:click="isActionModalOpen = false" wire:click="delete">Delete</button>
            </div>
        </div>
    </div>
    @endif
</section>
