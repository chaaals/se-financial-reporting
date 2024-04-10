<section x-data="{ isActionModalOpen: false }" class="w-full p-4">
    {{-- @if ($trial_balance)
        <section>
            @if ($editMode)
                <div>
                    <div>
                        <label for="report_name">Report Name:</label>
                        <input id="report_name" type="text" wire:model="editedReportName" placeholder="optional">
                    </div>
                    <div>
                        <label for="date">Trial Balance period:</label>
                        <input id='trialBalancePeriod' type='date' wire:model='editedDate' />
                    </div>
                    <div>
                        <label for="interim_period">Interim Period:</label>
                        <select id="interim_period" wire:model="editedInterimPeriod">
                            <option value="Quarterly">Quarterly</option>
                            <option value="Annual">Annual</option>
                        </select>
                    </div>
                    <div>
                        <label for="quarter">Quarter:</label>
                        <select id="quarter" wire:model="editedQuarter">
                            <option value="Q1">Q1</option>
                            <option value="Q2">Q2</option>
                            <option value="Q3">Q3</option>
                            <option value="Q4">Q4</option>
                        </select>
                    </div>
                    <div>
                        <label for="approved">Approved:</label>
                        <input id="approved" type="checkbox" wire:model="editedApproved" @if ($editedReportStatus === 'Draft') disabled @endif>
                    </div>
                    <div>
                        <label for="report_status">Report Status:</label>
                        @if (!$editedApproved)
                            <select id="report_status" wire:model="editedReportStatus">
                                @if (!$editedApproved)
                                    <option value="Draft">Draft</option>
                                    <option value="For Approval">For Approval</option>
                                @endif
                            </select>
                        @else
                            <input id="report_status" type="text" value="Approved" disabled>
                        @endif
                    </div>
                    <button wire:click="updateTrialBalance">Save</button>
                    <button wire:click="toggleEditMode">Cancel</button>
                </div>
            @else
                <div>Name: {{ $trial_balance->report_name }}</div>
                @if ($trial_balance->tb_type)
                    <div>Type: {{ $trial_balance->tb_type }}</div>
                @endif
                <div>Status: {{ $trial_balance->report_status }}</div>
                <div>Date: {{ $trial_balance->date }}</div>
                @if ($trial_balance->quarter)
                    <div>Quarter: {{ $trial_balance->quarter }}</div>
                @endif
                <div>Interim Period: {{ $trial_balance->interim_period }}</div>

                <div>
                    <!-- edit button -->
                    <button wire:click="toggleEditMode">Edit</button>
                    <!-- delete -->
                    <button wire:click="confirmDelete('{{ $trial_balance->tb_id }}')">Delete</button>
                    <!-- confirm deletion -->
                    @if ($confirming === $trial_balance->tb_id)
                        <button wire:click="deleteTrialBalance('{{ $trial_balance->tb_id }}')">Confirm Delete</button>
                        <button wire:click="$set('confirming', null)">Cancel</button>
                    @endif
                </div>

                <div>
                    {{-- export --}}
                    {{-- <button wire:click="export">Export</button>
                </div>
            @endif
        </section>
    @endif --}}

    {{-- header --}}
    <section class="w-full flex items-center justify-between flex-col bg-white rounded-lg mb-4 p-2 md:flex-row 2xl:mb-8">
        <h1 class="text-primary text-header font-bold font-inter">{{ $trial_balance->tb_name }}</h1>


        <section class="flex items-center gap-4">
            <livewire:financial-reporting.notes
                :reportId="$trial_balance->tb_id"
                :reportType="$reportType"
                :reportName="$trial_balance->tb_name" />
            
            <livewire:trial-balance.history />
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
                :data="$trial_balance_data->tb_data"
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
                    <p class="font-inter font-bold">{{ $trial_balance->date }}</p>
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
                <div class="mb-0.5">
                    <span class="text-xs font-inter text-slate-500">Updated At</span>
                    <p class="font-inter font-bold">{{ $trial_balance->updated_at }}</p>
                </div>
            </section>

            <section x-data="{ isToolTipVisible: false }" class="flex items-center gap-2">
                <button
                    class="w-full text-center @if($statusColor === 'draft') {{'bg-draft'}} @elseif($statusColor === 'forapproval') {{'bg-forapproval'}} @elseif($statusColor === 'approved') {{'bg-approved'}} @elseif($statusColor === 'changerequested') {{'bg-changerequested'}} @endif rounded-lg text-white p-2" x-on:click="isActionModalOpen = true">
                    {{ $trial_balance->tb_status }}
                </button>
                <div class="relative" x-on:mouseenter="isToolTipVisible = true" x-on:mouseleave="isToolTipVisible = false">
                    <x-financial-reporting.assets.info />

                    <div
                        x-cloak
                        x-show="isToolTipVisible"
                        class="absolute -left-46 -top-24 rounded-t-lg rounded-bl-lg bg-black bg-opacity-75 w-48 p-2 text-sm after:content-[''] after:absolute after:top-full after:left-2/4 after:ml-22 after:border-4 after:border-solid after:border-t-black after:border-opacity-75 after:border-r-transparent after:border-b-transparent after:border-l-transparent">
                        <p class="text-white">You can update the status of the report by clicking this button.</p>
                    </div>
                </div>
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

    @if(session('success'))
        <x-toast type="success">{{ session('success')}}</x-toast>
    @endif
</section>
