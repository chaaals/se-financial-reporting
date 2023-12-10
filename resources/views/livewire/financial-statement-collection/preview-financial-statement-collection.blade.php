<section
    x-data="{ showSFPO: true, showSFPE: false, showSCF: false, isActionModalOpen: false }"
    class="relative p-4">
    <section class="w-full flex items-center justify-between flex-col bg-white drop-shadow-md rounded-lg mb-4 p-2 gap-4 md:flex-row 2xl:mb-8">
        <section clas="flex flex-col items-center justify-center md:flex-row">
            <h1 class="text-primary text-header text-center font-bold font-inter md:text-left">{{ $fsCollection->collection_name }}</h1>
            <div class="flex items-center justify-center gap-2 md:pl-4 md:justify-start">
                <button
                    x-on:click="showSFPO=true;showSFPE=false;showSCF=false;"
                    class="w-20 p-1 rounded-lg md:w-28"
                    :class="showSFPO ? 'bg-primary text-white' : 'bg-transparent text-neutralFour'">
                    SFPO
                </button>
                <button
                    x-on:click="showSFPO=false;showSFPE=true;showSCF=false;"
                    class="w-20 p-1 rounded-lg md:w-28"
                    :class="showSFPE ? 'bg-primary text-white' : 'bg-transparent text-neutralFour'">
                    SFPE
                </button>
                <button
                    x-on:click="showSFPO=false;showSFPE=false;showSCF=true;"
                    class="w-20 p-1 rounded-lg md:w-28"
                    :class="showSCF ? 'bg-primary text-white' : 'bg-transparent text-neutralFour'">
                    SCF
                </button>
            </div>
        </section>

        <section class="flex items-center gap-4">
            <livewire:financial-reporting.notes
                :reportId="$fsCollection->collection_id"
                :reportType="$reportType"
                :reportName="$fsCollection->collection_name" />
            
            <button
                class="bg-secondary text-white px-4 py-2 rounded-lg text-xs md:text-base"
                wire:click="export">
                Export Financial Statements
            </button>
        </section>
    </section>

    <section class="flex flex-col gap-4 md:flex-row">
        {{-- placeholder for previews --}}
        <section class="w-full text-center sm:h-136 2xl:h-160">
        @foreach($financialStatements as $fsType=>$fs)
            <div class="h-full" x-cloak x-show="show{{$fsType}}">
                @if($fsType === "SFPO")
                <livewire:financial-reporting.financial-position-template
                    :data="$fs->fs_data"
                />
                @else
                <p class="text-sm whitespace-normal break-all w-80">{{ $fs->fs_data }}</p>
                @endif
            </div>
        @endforeach
        </section>
        
        <section class="w-full flex flex-col gap-4 justify-between bg-white rounded-lg p-4 md:w-72 md:h-136 2xl:h-160">
            <section>
                <div class="mb-0.5">
                    <span class="text-xs font-inter text-slate-500">Financial Statement Collection Name</span>
                    <p class="font-inter font-bold">{{ $fsCollection->collection_name }}</p>
                </div>
                <div class="mb-0.5">
                    <span class="text-xs font-inter text-slate-500">Date</span>
                    <p class="font-inter font-bold">{{ $fsCollection->date }}</p>
                </div>
                <div class="mb-0.5">
                    <span class="text-xs font-inter text-slate-500">Period</span>
                    <p class="font-inter font-bold">{{ $fsCollection->interim_period }}</p>
                </div>

                @if($fsCollection->quarter)
                <div class="mb-0.5">
                    <span class="text-xs font-inter text-slate-500">Quarter</span>
                    <p class="font-inter font-bold">{{ $fsCollection->quarter }}</p>
                </div>
                @endif
                <div class="mb-0.5">
                    <span class="text-xs font-inter text-slate-500">Created At</span>
                    <p class="font-inter font-bold">{{ $fsCollection->created_at }}</p>
                </div>
                <div class="mb-0.5">
                    <span class="text-xs font-inter text-slate-500">Updated At</span>
                    <p class="font-inter font-bold">{{ $fsCollection->updated_at }}</p>
                </div>
            </section>

            <section x-data="{ isToolTipVisible: false }" class="flex items-center gap-2">
                <button
                    class="w-full text-center @if($statusColor === 'draft') {{'bg-draft'}} @elseif($statusColor === 'forapproval') {{'bg-forapproval'}} @elseif($statusColor === 'approved') {{'bg-approved'}} @elseif($statusColor === 'changerequested') {{'bg-changerequested'}} @endif rounded-lg text-white p-2" x-on:click="isActionModalOpen = true">
                    {{ $fsCollection->collection_status }}
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
                @if($fsCollection->approved)
                    <p class="mb-2">The report has already been approved! Updating status of approved reports is not permitted.</p>
                @else
                    <p>Are you sure you want to change the report status?</p>
                    
                    <div class="w-full flex items-center justify-center gap-2 mb-2">
                        <p class="text-sm">From <strong>{{ $fsCollection->collection_status }}</strong></p>
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
                    @if($fsCollection->approved)
                        {{ "Close" }}
                    @else
                        {{ "No" }}
                    @endif
                </button>
                @if(!$fsCollection->approved)
                <button
                    class="text-white bg-accentTwo rounded-lg font-inter w-20 p-2" wire:click="updateFinancialStatementCollection"
                    x-on:click="isActionModalOpen = false">
                        Yes
                    </button>
                @endif
            </div>
        </div>
    </div>
</section>
