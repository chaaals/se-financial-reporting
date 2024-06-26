<section class="flex w-full p-4 gap-4">
    <section class="relative" x-data="{ uploading: false, monthly_active: false, quarterly_active: false, annual_active: false, import_active: false, selected_interim: false, loading: false }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-error="uploading = false">
        <form wire:submit.prevent="add">
            <div class="flex flex-col items-start mb-4">
                <label class="text-md font-bold" for='trialBalanceName'>Trial Balance Name</label>
                <input class="w-full rounded-lg focus:ring-0 md:w-96" id='trialBalanceName' type='text' wire:model='tbName' placeholder='Add trial balance name' />

                <div>@error('tbName')<span class="text-red-500">{{ $message }}</span>@enderror</div>
            </div>

            <div class="mb-4">
                <label class="text-md font-bold" for="interim_period">Period</label>

                <fieldset id="interim_period" class="flex items-center gap-4 pl-4 md:pl-8">
                    <section>
                        <input
                            type="radio"
                            id="Monthly"
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0""
                            value="Monthly"
                            wire:model="interimPeriod"
                            wire:click="resetImport"
                            x-on:click="monthly_active = true; quarterly_active = false; annual_active = false; selected_interim = false; loading = false" />
                        <label class="text-sm md:text-base" for="Monthly">Monthly</label>
                    </section>
                    <section>
                        <input
                            type="radio"
                            id="Quarterly"
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0""
                            value="Quarterly"
                            wire:model="interimPeriod"
                            wire:click="resetImport"
                            x-on:click="monthly_active = false; quarterly_active = true; annual_active = false; selected_interim = false; loading = false" />
                        <label class="text-sm md:text-base" for="Quarterly">Quarterly</label>
                    </section>
                    <section>
                        <input
                            type="radio"
                            id="Annual"
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0""
                            value="Annual"
                            wire:model="interimPeriod"
                            wire:click="resetImport"
                            x-on:click="monthly_active = false; quarterly_active = false; annual_active = true; selected_interim = true; loading = false"/>
                        <label class="text-sm md:text-base" for="Annual">Annual</label>
                    </section>
                </fieldset>
                
                <div>@error('interimPeriod')<span class="text-red-500">{{ $message }}</span>@enderror</div>
            </div>

            <div x-cloak x-show="monthly_active" class="flex flex-col gap-1 mb-2">
                <label class="text-md font-bold" for="month">Select Month</label>
                <select id="month" class="w-full text-xs appearance-none rounded-lg pr-8 md:w-96 md:text-sm disabled:text-slate-500 disabled:border-slate-500" wire:model="month" @if($importedFromGL) disabled @endif>
                            <option selected hidden>Select a Month</option>
                        @foreach($months as $val=>$label)
                            <option value='{{ $val }}' x-on:click="selected_interim = true;">{{ $label }}</option>
                        @endforeach
                </select>
            </div>

            <div x-cloak x-show="quarterly_active" class="mb-2">
                <label class="text-md font-bold" for="quarter">Quarter</label>

                <fieldset id="quarter" class="flex items-center gap-4 pl-4 md:pl-8">
                    <section>
                        <input class="checked:bg-black checked:hover:bg-secondary focus:ring-0"" type="radio" id="q1" value="Q1" wire:model="quarter" x-on:click="selected_interim = true;loading = false" @if($importedFromGL) disabled @endif />
                        <label for="q1">Q1</label>
                    </section>
                    <section>
                        <input class="checked:bg-black checked:hover:bg-secondary focus:ring-0"" type="radio" id="q2" value="Q2" wire:model="quarter" x-on:click="selected_interim = true;loading = false" @if($importedFromGL) disabled @endif />
                        <label for="q2">Q2</label>
                    </section>
                    <section>
                        <input class="checked:bg-black checked:hover:bg-secondary focus:ring-0"" type="radio" id="q3" value="Q3" wire:model="quarter" x-on:click="selected_interim = true;loading = false" @if($importedFromGL) disabled @endif />
                        <label for="q3">Q3</label>
                    </section>
                    <section>
                        <input class="checked:bg-black checked:hover:bg-secondary focus:ring-0"" type="radio" id="q4" value="Q4" wire:model="quarter" x-on:click="selected_interim = true;loading = false" @if($importedFromGL) disabled @endif />
                        <label for="q4">Q4</label>
                    </section>
                </fieldset>
            </div>

            <div x-cloak x-show="monthly_active || annual_active || quarterly_active" class="flex flex-col gap-1 mb-4">
                <label class="text-md font-bold" for="year">Year</label>
                <input class="w-full rounded-lg focus:ring-0 md:w-96 disabled:text-slate-400 disabled:border-slate-400" id='year' type='text' wire:model.live="year" placeholder='Enter year of the report' @if($importedFromGL) disabled @endif />
            </div>

            <div x-data="{ withQuarter: @entangle('quarter'), withMonth: @entangle('month') }" class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-md font-bold" for="source">Source</label>
                    @if($source)
                    <button type="button" class="hover:text-secondary" wire:click="resetImport" x-on:click="loading=false;selected_interim=false;">
                        <x-financial-reporting.assets.refresh-alt />
                    </button>
                    @endif
                </div>
                <div class="w-full h-44 relative mb-2 rounded-md bg-primary bg-opacity-5 border-2 border-dashed border-primary border-opacity-30 md:w-96">
                @if(!$importedFromGL)
                    <div class="w-full h-full flex flex-col items-center justify-center md:w-96">
                        <p x-show="!loading">
                            <strong>General Ledger Source Details</strong>
                        </p>

                        <button x-cloak x-show="(selected_interim || withQuarter || withMonth) && !loading" type="button" class="underline hover:text-secondary" wire:click="importFromGL" x-on:click="loading = true;">Preview</button>

                        <div class="relative w-4 h-4" x-cloak x-show='loading'>
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                @elseif($source)
                    <div class="p-4">
                        @if($source['accountCodes'] > 0)
                        <p class="mb-2">Successfully gathered financial information from <strong>{{ $source['accountCodes'] }}</strong> ledger entries.</p>
                        <p>
                            <strong>Grand Totals</strong>
                        </p>

                        <p>Debit Grand Totals &colon; {{ $source['debitGrandTotals'] }}</p>
                        <p>Credit Grand Totals &colon; {{ $source['creditGrandTotals'] }}</p>
                        @else
                            @if($interimPeriod == 'Monthly')
                            <p>No financial information were found from General Ledger for the month of {{ $months[$month] }} {{ $year }}</p>
                            @elseif($interimPeriod == 'Quarterly')
                            <p>No financial information were found from General Ledger for {{ $quarter }} {{ $year }}</p>
                            @else
                            <p>No financial information were found from General Ledger for {{ $year }}</p>
                            @endif

                            <p class="text-xs mt-2"><i>Click the refresh icon to reset current Trial Balance details.</i></p>
                        @endif
                    </div>
                @endif
                </div>

            </div>

            <section x-data="{ withGLSource: @entangle('tbData') }" class="w-full flex items-center justify-between md:w-96">
                <button class="border-accentOne border-2 rounded-lg px-4 py-2" type="button" wire:click="cancel">
                    Cancel
                </button>
                <button class="bg-primary text-white px-4 py-2 rounded-lg disabled:bg-opacity-50" type="submit" :disabled="withGLSource ? false : true">
                    Add
                </button>
            </section>
        </form>
    </section>

    <section class="hidden grow text-center md:block sm:h-136 2xl:h-160">
        @if($tbData && $source['accountCodes'] > 0)
        <livewire:financial-reporting.trial-balance-template
            :data="$tbData"
            :totalsData="$tbDataTotals"
        />
        @else
        <section class="w-full h-full flex items-center justify-center border-2 border-dashed border-primary">
            <p>Trial Balance Preview</p>
        </section>
        @endif

        @if($tbData && !$isTbBalanced)
        <section class="w-full flex p-4 mt-4 items-start border-2 border-dashed border-accentTwo rounded-lg">
            <p class="text-black text-left">WARNING: The generated Trial Balance report is unbalanced. Adding this report will send a notification to the General Ledger module for resolution.</p>
        </section>
        @endif
    </section>

    @if(session('success'))
        <x-toast type="success">{{ session('success')}}</x-toast>
    @endif
</section>
