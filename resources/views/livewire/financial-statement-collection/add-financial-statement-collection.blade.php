<section>
    <section class="relative p-4" x-data="{ isActionModalOpen: false ,quarterly_active: false, annual_active: false }">
        <form wire:submit.prevent="add">
            <div class="flex flex-col items-start mb-4">
                <label class="text-md font-bold" for='fsName'>Financial Statement Collection</label>
                <input class="w-full rounded-lg focus:ring-0 md:w-96" id='fsName' type='text' wire:model='fsName' placeholder='Add financial statement collection name' />
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
                            x-on:click="quarterly_active = true; annual_active = false"
                             />
                        <label class="text-sm md:text-base" for="Quarterly">Quarterly</label>
                    </section>
                    <section>
                        <input
                            type="radio"
                            id="Annual"
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0""
                            value="Annual"
                            wire:model.live="interimPeriod"
                            x-on:click="quarterly_active = false; annual_active = true"
                             />
                        <label class="text-sm md:text-base" for="Annual">Annual</label>
                    </section>
                </fieldset>
                <div class="mt-4">@error('interimPeriod')<span class="text-amber-500">{{ $message }}</span>@enderror</div>
            </div>

            <div x-cloak x-show="quarterly_active" class="mb-4">
                <label class="text-md font-bold" htmlFor='fsPeriod'>Quarter</label>
                <fieldset id="quarter" class="flex items-center gap-4 pl-4 md:pl-8">
                    <section>
                        <input
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0 disabled:checked:bg-opacity-50"
                            type="radio"
                            id="q1"
                            wire:model.live="quarter"
                            value="Q1" />
                        <label for="q1">Q1</label>
                    </section>
                    <section>
                        <input
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0 disabled:checked:bg-opacity-50"
                            type="radio"
                            id="q2"
                            wire:model.live="quarter"
                            value="Q2" />
                        <label for="q2">Q2</label>
                    </section>
                    <section>
                        <input
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0 disabled:checked:bg-opacity-50"
                            type="radio"
                            id="q3"
                            wire:model.live="quarter"
                            value="Q3" />
                        <label for="q3">Q3</label>
                    </section>
                    <section>
                        <input
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0 disabled:checked:bg-opacity-50"
                            type="radio"
                            id="q4"
                            wire:model.live="quarter"
                            value="Q4" />
                        <label for="q4">Q4</label>
                    </section>
                </fieldset>
            </div>

            <div x-cloak x-show="quarterly_active || annual_active" class="flex flex-col gap-1 mb-4">
                <label class="text-md font-bold" for="year">Year</label>
                <input class="w-full rounded-lg focus:ring-0 md:w-96 disabled:text-slate-400 disabled:border-slate-400" id='year' type='text' wire:model.live="year" placeholder='Enter year of the report' />
            </div>

            <div x-data="{isToolTipVisible: false}">
                <section class="flex flex-col justify-center gap-1.5">
                    @foreach($trialBalances as $key=>$tb)
                    <label
                        class="flex items-center gap-2 text-md font-bold @if(count($tb['options']) == 0) {{"text-neutral"}}@endif" for="trialBalances">
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
                    <select
                            id="trialBalances"
                            class="w-full text-lg appearance-none rounded-lg border-neutral pr-8 md:w-96 md:text-sm"
                            @if (count($tb['options']) == 0)
                                disabled
                            @endif
                            wire:model={{ $tb['model'] }}
                        >
                            <option selected hidden>{{ $key }}</option>
                            @if(count($tb['options']) > 0)
                                @foreach($tb['options'] as $i=>$option)
                                    <option value='{{ $option['tb_id'] }}'>{{ $option['tb_name'] }}</option>
                                @endforeach
                            @endif
                    </select>
                    @endforeach
                </section>
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
                
                <fieldset id="interim_period" class="gap-4 pl-4 md:w-96 md:pl-8 md:flex-row md:flex-wrap md:items-center">
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
                    <section>
                        <input
                            type="checkbox"
                            id="SCNAE"
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0 disabled:checked:bg-opacity-50"
                            value="SCNAE"
                            wire:model="fsTypes"
                            disabled />
                        <label class="text-sm md:text-base" for="SCNAE">Changes in Net Assets/Equity</label>
                    </section>
                    <section>
                        <input
                            type="checkbox"
                            id="SCBAA"
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0 disabled:checked:bg-opacity-50"
                            value="SCBAA"
                            wire:model="fsTypes"
                            disabled />
                        <label class="text-sm md:text-base" for="SCBAA">Comparison of Budget and Actual Amounts</label>
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
    </section>

    @if(session('success'))
        <x-toast type="success">{{ session('success')}}</x-toast>
    @endif
</section>
