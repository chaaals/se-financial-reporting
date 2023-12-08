<section x-data="{ showSuggestions: false }" class=" relative w-full md:w-96">
    <div x-data="{ isToolTipVisible: false }" class="relative w-full flex flex-col items-start mb-4">
        <label
            class="flex items-center gap-2 text-md font-bold @if(!$interimPeriod) {{"text-neutral"}}@endif" for="tbSuggestionSearch">
            Trial Balance

            <div class="relative" x-on:mouseenter="isToolTipVisible = true" x-on:mouseleave="isToolTipVisible = false">
                <x-financial-reporting.assets.info />
                <div
                    x-cloak
                    x-show="isToolTipVisible"
                    class="absolute -left-46 -top-20 rounded-t-lg rounded-bl-lg bg-black bg-opacity-75 w-48 p-2 text-sm after:content-[''] after:absolute after:top-full after:left-2/4 after:ml-22 after:border-4 after:border-solid after:border-t-black after:border-opacity-75 after:border-r-transparent after:border-b-transparent after:border-l-transparent">
                    <p class="text-white text-xs">
                        Kindly select an interim period first before searching for a Trial Balance.
                    </p>
                </div>
            </div>
        </label>
        <input
            class="w-full rounded-lg disabled:placeholder-neutral disabled:border-neutral focus:ring-0 md:w-96"
            id="tbSuggestionSearch"
            type="text"
            placeholder="Search a trial balance..."
            autocomplete="off"
            wire:model.live.debounce="searchInput"
            x-on:click="showSuggestions = true"
            x-on:click.outside="showSuggestions = false"
            @if(!$interimPeriod) disabled @endif
            />
    </div>

    <ul x-cloak x-show="showSuggestions" role="listbox" class="w-full max-h-96 absolute top-20 left-0 bg-white drop-shadow-md rounded-lg overflow-hidden overflow-y-scroll scrollbar z-10">
        @if(count($suggestions) > 0)
            @foreach($suggestions as $index=>$item)
                <li
                    class="hover:bg-active hover:bg-opacity-50 cursor-pointer p-4" wire:click="setSelectedInput({{$index}})"
                    x-on:click="showSuggestions = false">
                    <h3 class="text-base font-inter font-bold">{{ $item->tb_name }}</h3>
                    <div class="flex items-center gap-2">
                        <p class="text-sm text-neutral font-inter font-light">
                            {{ $item->interim_period }}
                        </p>
                        <p class="text-sm text-neutral font-inter font-light">
                            {{ date('M d, Y', strtotime($item->date)) }}
                        </p>
                    </div>
                </li>
            @endforeach
        @else
            <li class="cursor-pointer p-4">
                No results for <strong>{{ $searchInput }}</strong> was found.
            </li>
        @endif
    </ul>
</section>