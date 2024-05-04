<section class="flex w-full p-4 gap-4">
    <section class="relative" x-data="{ uploading: false, quarterly_active: false, import_active: false, update_existing: false }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-error="uploading = false">
        <form wire:submit.prevent="add">
            <div class="flex flex-col items-start mb-4">
                <label class="text-md font-bold" for='trialBalanceName'>Trial Balance Name</label>
                <input class="w-full rounded-lg focus:ring-0 md:w-96" id='trialBalanceName' type='text' wire:model='tbName' placeholder='Add trial balance name' />
            </div>

            <div class="mb-4">
                <label class="text-md font-bold" for="update_tb">Update Existing TB?</label>

                <fieldset id="update_tb" class="flex items-center gap-4 pl-4 md:pl-8">
                    <section>
                        <input type="checkbox" id="update_tb_checkbox" class="checked:bg-black checked:hover:bg-secondary focus:ring-0" wire:model="updateExistingTb" wire:click="tbList" x-on:click="update_existing = !update_existing" />
                        <label class="text-sm md:text-base" for="update_tb_checkbox">Yes</label>
                    </section>
                </fieldset>
            </div>

            <div x-cloak x-show="update_existing">
                <div class="mb-4">
                    <label class="text-md font-bold" for="tb_list">Select TB</label>
                    <select id="tb_list" wire:model="updateExistingTbId">
                        @foreach($tbList as $tb)
                        <option value="{{ $tb['id'] }}">{{ $tb['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-col items-start mb-4" x-cloak x-show="!update_existing">
                <label class="text-md font-bold" for='trialBalancePeriod'>Date</label>
                <input class="w-full rounded-lg focus:ring-0 md:w-96" id='trialBalancePeriod' type='date' wire:model='tbDate' />
                <div>@error('tbDate')<span>{{ $message }}</span>@enderror</div>
            </div>

            <div class="mb-4" x-show="!update_existing">
                <label class="text-md font-bold" for="interim_period">Period</label>

                <fieldset id="interim_period" class="flex items-center gap-4 pl-4 md:pl-8">
                    <section>
                        <input
                            type="radio"
                            id="Monthly"
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0""
                            value="Monthly"
                            wire:model="interimPeriod"
                            x-on:click="quarterly_active = false" />
                        <label class="text-sm md:text-base" for="Monthly">Monthly</label>
                    </section>
                    <section>
                        <input
                            type="radio"
                            id="Quarterly"
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0""
                            value="Quarterly"
                            wire:model="interimPeriod"
                            x-on:click="quarterly_active = true" />
                        <label class="text-sm md:text-base" for="Quarterly">Quarterly</label>
                    </section>
                    <section>
                        <input
                            type="radio"
                            id="Annual"
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0""
                            value="Annual"
                            wire:model="interimPeriod"
                            x-on:click="quarterly_active = false"/>
                        <label class="text-sm md:text-base" for="Annual">Annual</label>
                    </section>
                </fieldset>
                
                <div>@error('interimPeriod')<span>{{ $message }}</span>@enderror</div>
            </div>

            <div x-cloak x-show="quarterly_active && !update_existing" class="mb-4">
                <label class="text-md font-bold" for="quarter">Quarter</label>

                <fieldset id="quarter" class="flex items-center gap-4 pl-4 md:pl-8">
                    <section>
                        <input class="checked:bg-black checked:hover:bg-secondary focus:ring-0"" type="radio" id="q1" value="Q1" wire:model="quarter" />
                        <label for="q1">Q1</label>
                    </section>
                    <section>
                        <input class="checked:bg-black checked:hover:bg-secondary focus:ring-0"" type="radio" id="q2" value="Q2" wire:model="quarter" />
                        <label for="q2">Q2</label>
                    </section>
                    <section>
                        <input class="checked:bg-black checked:hover:bg-secondary focus:ring-0"" type="radio" id="q3" value="Q3" wire:model="quarter" />
                        <label for="q3">Q3</label>
                    </section>
                    <section>
                        <input class="checked:bg-black checked:hover:bg-secondary focus:ring-0"" type="radio" id="q4" value="Q4" wire:model="quarter" />
                        <label for="q4">Q4</label>
                    </section>
                </fieldset>
            </div>

            <div class="mb-4">
                <label class="text-md font-bold" for="source">Source</label>

                <fieldset id="source" class="flex items-center gap-4 pl-4 md:pl-8">
                    <section>
                        <input
                            type="radio"
                            id="import"
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0"
                            value="import"
                            wire:model="source"
                            x-on:click="import_active = true" />
                        <label class="text-sm md:text-base" for="import">Import Trial Balance</label>
                    </section>
                    <section>
                        <input
                            type="radio"
                            id="general_ledger"
                            class="checked:bg-black checked:hover:bg-secondary focus:ring-0"
                            value="general_ledger"
                            wire:model="source"
                            x-on:click="import_active = false"
                            wire:click="resetImport" />
                        <label class="text-sm md:text-base" for="import">From General Ledger</label>
                    </section>
                </fieldset>
            </div>

            <div x-cloak x-show="import_active" class="w-full h-44 relative mb-4 rounded-md bg-primary bg-opacity-5 border-2 border-dashed border-primary border-opacity-30 md:w-96">
                <label
                    class="w-full h-full flex flex-col items-center justify-center md:w-96"
                    for="file-upload"
                    x-show="!uploading">

                    @if($importedSpreadsheet)
                        <p>{{ $importedSpreadsheet->getClientOriginalName() }}</p>
                    @else
                        <x-financial-reporting.assets.upload />
                        <p>Drag & drop files or Browse</p>
                    @endif

                </label>
                <input
                    id="file-upload"
                    class="opacity-0 absolute top-0 left-0 w-full h-44 z-10"
                    type="file"
                    accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel""
                    wire:model.live="importedSpreadsheet"
                />

                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2" x-show="uploading" x-cloak>
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                
                <div>@error('importedSpreadsheet')<span>{{ $message }}</span>@enderror</div>
            </div>

            <section class="w-full flex items-center justify-between md:w-96">
                <button class="border-accentOne border-2 rounded-lg px-4 py-2" type="button" wire:click="cancel">
                    Cancel
                </button>
                <button class="bg-primary text-white px-4 py-2 rounded-lg" type="submit">
                    Add
                </button>
            </section>
        </form>
    </section>

    <section class="hidden grow text-center md:block sm:h-136 2xl:h-160">
        @if($tbData)
        <livewire:financial-reporting.trial-balance-template
            :data="$tbData"
        />
        @else
        <section class="w-full h-full flex items-center justify-center border-2 border-dashed border-primary">
            <p>Trial Balance Preview</p>
        </section>
        @endif

        @if($tbData && !$isTbBalanced)
        <section class="w-full flex p-4 mt-4 items-start border-2 border-dashed border-secondary rounded-lg">
            <p class="text-secondary text-left">WARNING: The generated Trial Balance report is unbalanced. Adding this report will send a notification to the General Ledger module for resolution.</p>
        </section>
        @endif
    </section>

    @if(session('success'))
        <x-toast type="success">{{ session('success')}}</x-toast>
    @endif
</section>
