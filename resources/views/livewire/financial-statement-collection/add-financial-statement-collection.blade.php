<section>
    <section class="relative p-4" x-data="{ isActionModalOpen: false ,quarterly_active: false }">
        <form wire:submit.prevent="add">
            <div class="flex flex-col items-start mb-4">
                <label class="text-md font-bold" for='fsName'>Financial Statement Collection</label>
                <input class="w-full rounded-lg focus:ring-0 md:w-96" id='fsName' type='text' wire:model='fsName' placeholder='Add financial statement collection name' @if($confirming) disabled @endif/>
            </div>

            <div class="flex flex-col items-start">
                <label class="text-md font-bold" for="fsDate">Date</label>
                <input class="w-full rounded-lg focus:ring-0 md:w-96" id="fsDate" type="date" wire:model.live="date" />
                <div class="mt-4">@error('date')<span class="text-amber-500">{{ $message }}</span>@enderror</div>
            </div>

            <div class="flex flex-col items-start">
                <label class="text-md font-bold" for="interim_period">Interim Period</label>

                <fieldset id="interim_period" class="flex items-center gap-4 pl-4 md:pl-8">
                    <section>
                        <input
                            type="radio"
                            id="Quarterly"
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0""
                            value="Quarterly"
                            wire:model.live="interimPeriod"
                            x-on:click="quarterly_active = true"
                            @if($confirming) disabled @endif />
                        <label class="text-sm md:text-base" for="Quarterly">Quarterly</label>
                    </section>
                    <section>
                        <input
                            type="radio"
                            id="Annual"
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0""
                            value="Annual"
                            wire:model.live="interimPeriod"
                            x-on:click="quarterly_active = false"
                            @if($confirming) disabled @endif />
                        <label class="text-sm md:text-base" for="Annual">Annual</label>
                    </section>
                </fieldset>
                <div class="mt-4">@error('interimPeriod')<span class="text-amber-500">{{ $message }}</span>@enderror</div>
            </div>

            @if($date)
            <div x-cloak x-show="quarterly_active" class="mb-4">
                <label class="text-md font-bold" htmlFor='fsPeriod'>Quarter</label>
                <fieldset id="quarter" class="flex items-center gap-4 pl-4 md:pl-8">
                    <section>
                        <input
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0 disabled:checked:bg-opacity-50"
                            type="radio"
                            id="q1"
                            wire:model="quarter"
                            value="Q1"
                            disabled />
                        <label for="q1">Q1</label>
                    </section>
                    <section>
                        <input
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0 disabled:checked:bg-opacity-50"
                            type="radio"
                            id="q2"
                            wire:model="quarter"
                            value="Q2"
                            disabled />
                        <label for="q2">Q2</label>
                    </section>
                    <section>
                        <input
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0 disabled:checked:bg-opacity-50"
                            type="radio"
                            id="q3"
                            wire:model="quarter"
                            value="Q3"
                            disabled />
                        <label for="q3">Q3</label>
                    </section>
                    <section>
                        <input
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0 disabled:checked:bg-opacity-50"
                            type="radio"
                            id="q4"
                            wire:model="quarter"
                            value="Q4"
                            disabled />
                        <label for="q4">Q4</label>
                    </section>
                </fieldset>
            </div>
            @endif

            <div>
                <livewire:financial-reporting.suggestion-search
                    :interimPeriod="$interimPeriod"
                />
                <div class="mb-4">@error('tbID')<span class="text-amber-500">{{ $message }}</span>@enderror</div>
            </div>

            <div x-data="{ isToolTipVisible: false }" class="flex flex-col items-start mb-4">
                <label class="flex items-center gap-2 text-md font-bold" for="interim_period">
                    Statement Types
                    <div class="relative" x-on:mouseenter="isToolTipVisible = true" x-on:mouseleave="isToolTipVisible = false">
                    <x-financial-reporting.assets.info />

                        <div
                            x-cloak
                            x-show="isToolTipVisible"
                            class="absolute -left-46 -top-20 rounded-t-lg rounded-bl-lg bg-black bg-opacity-75 w-48 p-2 text-sm after:content-[''] after:absolute after:top-full after:left-2/4 after:ml-22 after:border-4 after:border-solid after:border-t-black after:border-opacity-75 after:border-r-transparent after:border-b-transparent after:border-l-transparent">
                            <p class="text-white text-xs">
                                Financial Statements must be complete. Incomplete Financial Statements is not permitted upon creating a collection
                            </p>
                        </div>
                    </div>
                </label>
                
                <fieldset id="interim_period" class="flex flex-col gap-4 pl-4 md:w-96 md:pl-8 md:flex-row md:flex-wrap md:items-center">
                    <section>
                        <input
                            type="checkbox"
                            id="SFPO"
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0 disabled:checked:bg-opacity-50"
                            value="SFPO"
                            wire:model="fsTypes"
                            disabled />
                        <label class="text-sm md:text-base" for="SFPO">Financial Position</label>
                    </section>
                    <section>
                        <input
                            type="checkbox"
                            id="SFPE"
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0 disabled:checked:bg-opacity-50"
                            value="SFPE"
                            wire:model="fsTypes"
                            disabled />
                        <label class="text-sm md:text-base" for="SFPE">Financial Performance</label>
                    </section>
                    <section>
                        <input
                            type="checkbox"
                            id="SCF"
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0 disabled:checked:bg-opacity-50"
                            value="SCF"
                            wire:model="fsTypes"
                            disabled />
                        <label class="text-sm md:text-base" for="SCF">Cash Flow</label>
                    </section>
                </fieldset>
            </div>

            <section class="w-full flex items-center justify-between md:w-96">
                <button class="border-accentOne border-2 rounded-lg px-4 py-2" type="button" wire:click="cancel">Cancel</button>
                <button
                    class="bg-primary text-white px-4 py-2 rounded-lg"
                    type="submit"
                    x-on:click="isActionModalOpen = true">
                    Add
                </button>
            </section>
        </form>
        <!-- show confirmation -->
        {{-- @if ($confirming)
            <div>
                <span>Add all financial statements?</span>
                <button wire:click="addFS">Yes</button>
                <button wire:click="cancelAddFS">No</button>
            </div>
        @endif --}}
    </section>
</section>
