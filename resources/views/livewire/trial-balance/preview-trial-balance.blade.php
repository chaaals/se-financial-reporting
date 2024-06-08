<section
    x-data="{ isActionModalOpen: false, isMailFormOpen: false, isRebalancingFormOpen: false }"
    class="w-full p-4"
    >
    <section class="w-full flex items-center justify-between flex-col bg-white rounded-lg mb-4 p-2 md:flex-row 2xl:mb-8">
        <h1 class="text-primary text-header font-bold font-inter">
        @if($trial_balance->deleted_at)
        &lpar;Archived&rpar; {{ $trial_balance->tb_name }}
        @else
        {{ $trial_balance->tb_name }}
        @endif
        </h1>

        <section class="flex items-center gap-4">
            <livewire:financial-reporting.notes
                :reportId="$trial_balance->tb_id"
                :reportType="$reportType"
                :reportName="$trial_balance->tb_name" />
            @if(count($all_tb_data) > 1)
            <section
                class="relative w-10 h-10 flex items-center justify-center hidden md:block"
                x-data="{ isHistoryVisible: false }"
                x-on:click.outside="isHistoryVisible = false">
                <section class="w-full h-full flex items-center justify-center">
                    <button class="relative" x-on:click="isHistoryVisible = true">
                        <x-financial-reporting.assets.history />
                    </button>
                </section>

                <section
                    x-cloak
                    x-show="isHistoryVisible"
                    class="absolute top-0 right-0 w-96 bg-white custom-dropshadow z-10 rounded-lg md:flex md:flex-col"
                >
                    <section class="p-2">
                        <h3 class="text-lg font-bold">Version History</h3>
                    </section>
                @foreach($all_tb_data as $i => $tb_data)
                    <section class="w-full cursor-pointer p-2 hover:text-secondary"
                     x-on:click="isHistoryVisible = false"
                     wire:click="setActiveTrialBalanceData({{$i}})">
                        <div>@if($i <= 0)<strong>Latest</strong>@else<strong>v{{count($all_tb_data) - $i}}</strong>@endif &middot; {{ date('M d, Y H:i A', strtotime($tb_data["created_at"])) }}</div>
                    </section>
                @endforeach
                </section>
            </section>
            @endif
            @if($isBalanced && $trial_balance->approved)
            <section class="w-10 h-10 flex items-center justify-center">
                <button
                class="relative"
                wire:click="writeReport"
                x-on:click="isMailFormOpen = true"
                >
                    <x-financial-reporting.assets.mail />
                </button>
            </section>
            @endif
            <button
                wire:click="export"
                class="bg-secondary text-white px-4 py-2 rounded-lg text-xs md:text-base">
                Export Trial Balance
            </button>
        </section>
    </section>

    <section class="flex flex-col gap-4 md:flex-row">
        {{-- placeholder for previews --}}
        <section class="w-full text-center sm:h-136 2xl:h-160">
            <livewire:financial-reporting.trial-balance-template
                key="{{ now() }}"
                :data="$trial_balance_data['tb_data']"
                :totalsData="$trial_balance_data['totals_data']"
            />
        </section>
        
        <section class="w-full flex flex-col gap-4 justify-between bg-white rounded-lg p-4 md:w-72 md:h-136 2xl:h-160">
            <section>
                <div class="mb-0.5">
                    <span class="text-xs font-inter text-slate-500">Trial Balance Name</span>
                    <p class="font-inter font-bold">{{ $trial_balance->tb_name }}</p>
                </div>
                <div class="mb-0.5">
                    <span class="text-xs font-inter text-slate-500">Date</span>
                    <p class="font-inter font-bold">{{ $trial_balance->tb_date }}</p>
                </div>
                <div class="mb-0.5">
                    <span class="text-xs font-inter text-slate-500">Period</span>
                    <p class="font-inter font-bold">{{ $trial_balance->interim_period }}</p>
                </div>

                @if($trial_balance->quarter)
                <div class="mb-0.5">
                    <span class="text-xs font-inter text-slate-500">Quarter</span>
                    <p class="font-inter font-bold">{{ $trial_balance->quarter }}</p>
                </div>
                @endif
                <div class="mb-0.5">
                    <span class="text-xs font-inter text-slate-500">Created At</span>
                    <p class="font-inter font-bold">{{ $trial_balance->created_at }}</p>
                </div>
                <div class="mb-2 border-b-2 border-slate-300">
                    <span class="text-xs font-inter text-slate-500">Updated At</span>
                    <p class="font-inter font-bold">{{ $trial_balance->updated_at }}</p>
                </div>
                <div class="mb-0.5">
                    <div class="flex w-full justify-between items-center mb-0.5">
                        <span class="text-xs font-inter text-slate-500">Grand Totals</span>
                    </div>
                    <p class="font-inter font-bold">Debit &colon; Php {{ $trial_balance->debit_grand_totals }}</p>
                    <p class="font-inter font-bold">Credit &colon; Php {{ $trial_balance->credit_grand_totals }}</p>
                </div>
            </section>

            <section class="w-full">
            </section>

            <section class="w-full flex gap-2 flex-col gap-2">
                <section x-data="{ isToolTipVisible: false }" class="flex items-center gap-2">
                    <div class="w-full flex flex-col gap-2">
                        <button
                            class="w-full text-center @if($statusColor === 'draft') {{'bg-draft'}} @elseif($statusColor === 'forapproval') {{'bg-forapproval'}} @elseif($statusColor === 'approved') {{'bg-approved'}} @elseif($statusColor === 'changerequested') {{'bg-changerequested'}} @endif rounded-lg text-white p-2" x-on:click="isActionModalOpen = true"
                            @if($trial_balance->deleted_at) disabled @endif
                            >
                            {{ $trial_balance->tb_status }}
                        </button>
                        {{-- TODO: Disable if already balanced --}}
                        @if(!$isBalanced && !$trial_balance->approved && auth()->user()->role === "accounting")
                        <button 
                            class="w-full text-center rounded-lg text-white p-2 bg-primary"
                            {{-- wire:click='rebalance' --}}
                            x-on:click="isRebalancingFormOpen = true"
                            @if($isBalanced || $trial_balance->deleted_at) disabled @endif
                        >
                            Rebalance
                        </button>
                        @endif
                    </div>
                </section>
            </section>
        </section>
    </section>


    {{-- Modal --}}
    <div
        x-cloak
        x-show="isActionModalOpen"
        role="dialog"
        class="fixed top-0 left-0 w-screen h-screen bg-neutral bg-opacity-50 flex items-center justify-center">
        <div class="w-80 bg-white drop-shadow-md p-4 rounded-lg">
            <h1 class="text-2xl font-bold font-inter mb-2">Change Report Status</h1>

            <div class="flex items-center flex-col gap-2">
                @if($trial_balance->approved)
                    <p class="mb-2">The report has already been approved! Updating status of approved reports is not permitted.</p>
                @else
                    <p>Are you sure you want to change the report status?</p>
                    
                    <div class="w-full flex items-center justify-center gap-2 mb-2">
                        <p class="text-sm">From <strong>{{ $trial_balance->tb_status }}</strong></p>
                        <span class="text-sm">to</span>
                            {{-- TODO: Change in the future, sync with integ team for user roles --}}
                            {{-- TODO: Modify p tags to input for wire:model --}}
                        @if(count($reportStatusOptions) > 0)
                        <select class="w-20 text-xs appearance-none rounded-lg border-neutral pr-8 md:w-24 md:text-sm" wire:model="selectedStatusOption">
                            @foreach($reportStatusOptions as $option)
                            <option value="{{$option}}">{{ $option }}</option>
                            @endforeach
                        </select>
                        @else
                        <p class="text-sm"><strong>{{$selectedStatusOption}}</strong></p>
                        @endif
                    </div>
                @endif
            </div>
            <div class="w-full flex justify-between items-center">
                <button class="text-white bg-neutral rounded-lg font-inter w-20 p-2" x-on:click="isActionModalOpen = false">
                    @if($trial_balance->approved)
                        {{ "Close" }}
                    @else
                        {{ "No" }}
                    @endif
                </button>
                @if(!$trial_balance->approved)
                <button
                    class="text-white bg-accentTwo rounded-lg font-inter w-20 p-2" wire:click="updateTrialBalance"
                    x-on:click="isActionModalOpen = false">
                        Yes
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div
        x-cloak
        x-show="isMailFormOpen"
        role="dialog"
        class="fixed top-0 left-0 w-screen h-screen bg-neutral bg-opacity-50 flex items-center justify-center">
        <div class="w-1/3 bg-white drop-shadow-md p-4 rounded-lg">
            <h1 class="text-2xl font-bold font-inter mb-2">Report Email Details</h1>

            <div class="flex flex-col gap-2">
                <form wire:submit.prevent='mailReport'>
                    <div class="flex flex-col items-start mb-4">
                    <label class="text-md font-bold" for='trialBalanceName'>Subject</label>
                    <input class="w-full rounded-lg focus:ring-0" id='trialBalanceName' type='text' wire:model='subject' placeholder='Enter subject' />
                    <div>@error('subject')<span class="text-red">{{ $message }}@enderror</span></div>
                    </div>

                    <div class="mb-4">
                    <label class="text-md font-bold" for='trialBalanceName'>To:</label>
                    <input class="w-full rounded-lg focus:ring-0" id='trialBalanceName' type='email' wire:model.live='receiver' placeholder='Enter recipient' />
                    <div>@error('receiver')<span class="text-red">{{ $message }}@enderror</span></div>
                    </div>

                    {{-- <div class="mb-4">
                    <label class="text-md font-bold" for='trialBalanceName'>Cc:</label>
                    <input class="w-full rounded-lg focus:ring-0" id='trialBalanceName' type='email' placeholder='Enter recipient' />
                    </div> --}}

                    <div class="mb-4">
                    <label class="text-md font-bold" for='trialBalanceName'>Body</label>
                    <textarea class="w-full p-2 rounded-lg focus:ring-0" id='trialBalanceName' wire:model='message' placeholder='Write a message' ></textarea>
                    <div>@error('message')<span class="text-red">{{ $message }}@enderror</span></div>
                    </div>

                    <div class="mb-4">
                        <label class="text-md font-bold" for='trialBalanceName'>Attachment</label>
                        @if($filename && !$isWriting)
                        <p>{{ $filename }}</p>
                        @else
                        <div class="w-full flex items-center justify-center">
                            <div class="relative w-4 h-4">
                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2" x-cloak>
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <section class="w-full flex items-center justify-between gap-4">
                        <button
                            class="w-1/2 bg-accentOne px-4 py-2 rounded-lg"
                            type="button"
                            x-on:click="isMailFormOpen = false"
                        >
                        Close
                        </button>
                        <button
                        class="w-full bg-primary text-white px-4 py-2 rounded-lg disabled:bg-opacity-50" type="submit"
                        @if(!$filename) disabled @endif
                        >Send Report</button>
                    </section>
                </form>
            </div>
        </div>
    </div>

    <div
        x-cloak
        x-show="isRebalancingFormOpen"
        role="dialog"
        class="fixed top-0 left-0 w-screen h-screen bg-neutral bg-opacity-50 flex items-center justify-center">
        <div class="w-1/3 bg-white drop-shadow-md p-4 rounded-lg">
            <h1 class="text-2xl font-bold font-inter mb-2">Rebalancing Details</h1>

            <div class="flex flex-col gap-2">
                <div class="flex flex-col items-start mb-2">
                    <label class="text-md font-bold" for='trialBalanceName'>Trial Balance</label>
                    <input class="w-full rounded-lg focus:ring-0 disabled:text-slate-400 disabled:border-slate-400" id='trialBalanceName' type='text' value="{{$trial_balance->tb_name}}" disabled />
                </div>
                <div class="flex flex-col items-start mb-2">
                    <label class="text-md font-bold" for='trialBalanceName'>Date</label>
                    <input class="w-full rounded-lg focus:ring-0 disabled:text-slate-400 disabled:border-slate-400" id='trialBalanceName' type='text' value="{{date("M d, Y", strtotime($trial_balance->tb_date))}}" disabled />
                </div>
                <div class="flex flex-col items-start mb-2">
                    <label class="text-md font-bold" for='trialBalanceName'>Interim Period</label>
                    <input class="w-full rounded-lg focus:ring-0 disabled:text-slate-400 disabled:border-slate-400" id='trialBalanceName' type='text' value="{{$trial_balance->interim_period}}" disabled />
                </div>
                @if($trial_balance->quarter)
                <div class="flex flex-col items-start mb-2">
                    <label class="text-md font-bold" for='trialBalanceName'>Quarter</label>
                    <input class="w-full rounded-lg focus:ring-0 disabled:text-slate-400 disabled:border-slate-400" id='trialBalanceName' type='text' value="{{$trial_balance->quarter}}" disabled />
                </div>
                @elseif($trial_balance->interim_period == "Monthly")
                <div class="flex flex-col items-start mb-2">
                    <label class="text-md font-bold" for='trialBalanceName'>Month</label>
                    <input class="w-full rounded-lg focus:ring-0 disabled:text-slate-400 disabled:border-slate-400" id='trialBalanceName' type='text' value="{{date("M", strtotime($trial_balance->tb_date))}}" disabled />
                </div>
                @elseif($trial_balance->interim_period == "Annual")
                <div class="flex flex-col items-start mb-2">
                    <label class="text-md font-bold" for='trialBalanceName'>Year</label>
                    <input class="w-full rounded-lg focus:ring-0 disabled:text-slate-400 disabled:border-slate-400" id='trialBalanceName' type='text' value="{{date("Y", strtotime($trial_balance->tb_date))}}" disabled />
                </div>
                @endif
                <section x-data="{isRebalancing: false, hasRebalanced: false, rebalancedMessage: ''}" x-init="Livewire.on('rebalanced', message => { isRebalancing = false; hasRebalanced = true; rebalancedMessage = message; })">
                
                    <p class="mb-2" x-cloak x-show="!hasRebalanced"><i>Note&colon; By clicking proceed, ledger entries will be refetched using the following information in attempt to rebalance the report.</i></p>

                    <p class="mb-2" x-cloak x-show="hasRebalanced"><i>Result&colon; Successful rebalancing attempt. <strong><span x-text="rebalancedMessage"></span></strong></i></p>

                    <section class="w-full flex items-center justify-between gap-4">
                        <button
                            class="w-full bg-accentOne px-4 py-2 rounded-lg disabled:bg-opacity-50"
                            type="button"
                            x-on:click="isRebalancingFormOpen = false; hasRebalanced = false; rebalancedMessage = '';"
                            :disabled="isRebalancing"
                        >Close</button>
                        <button
                            x-cloak
                            x-show="!hasRebalanced"
                            class="w-full bg-primary text-white px-4 py-2 rounded-lg disabled:bg-opacity-50 " type="button"
                            x-on:click='isRebalancing = true'
                            wire:click='rebalance'
                            :disabled="isRebalancing"
                            >
                            <p x-cloak x-show="!isRebalancing">
                            Proceed
                            </p>
                        <div class="w-full flex items-center justify-center py-1" x-cloak x-show="isRebalancing">
                                <div class="relative w-4 h-4">
                                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            </button>
                    </section>

                </section>
            </div>
        </div>
    </div>

    @if(session('success'))
        <x-toast type="success">{{ session('success')}}</x-toast>
    @endif
</section>
