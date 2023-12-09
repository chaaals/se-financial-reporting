<section
    x-data="{ showSFPO: true, showSFPE: false, showSCF: false }"
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
        <section class="w-full border-2 border-dashed border-primary text-center sm:h-136 2xl:h-160">
        @foreach($financialStatements as $fsType=>$fs)
            <p x-cloak x-show="show{{$fsType}}" class="text-sm whitespace-normal break-all w-80">{{ $fs->fs_data }}</p>
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
    {{-- @if ($fsCollection)
        <section>
            @if ($editMode)
                <div>
                    <div>
                        <label for="collection_name">Report Name:</label>
                        <input id="collection_name" type="text" wire:model="editedFSCName" placeholder="optional">
                    </div>
                    <div>
                        <label for="date">Financial Statement period:</label>
                        <input id='date' type='date' wire:model='editedDate' />
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
                        <input id="approved" type="checkbox" wire:model="editedApproved" @if ($editedFSCStatus === 'Draft') disabled @endif>
                    </div>
                    <div>
                        <label for="collection_status">Report Status:</label>
                        @if (!$editedApproved)
                            <select id="collection_status" wire:model="editedFSCStatus">
                                @if (!$editedApproved)
                                    <option value="Draft">Draft</option>
                                    <option value="For Approval">For Approval</option>
                                @endif
                            </select>
                        @else
                            <input id="collection_status" type="text" value="Approved" disabled>
                        @endif
                    </div>
                    <button wire:click="updateFinancialStatementCollection">Save</button>
                    <button wire:click="toggleEditMode">Cancel</button>
                </div>
            @else
                <div>Name: {{ $fsCollection->collection_name }}</div>
                @if ($fsCollection->fs_type)
                    <div>Type: {{ $fsCollection->fs_type }} (using template {{ $fsCollection->template_name}})</div>
                @endif
                <div>Status: {{ $fsCollection->collection_status }}</div>
                <div>Date: {{ $fsCollection->date }}</div>
                @if ($fsCollection->quarter)
                    <div>Quarter: {{ $fsCollection->quarter }}</div>
                @endif
                <div>Interim Period: {{ $fsCollection->interim_period }}</div>
                <!-- previewing fs -->
                <div>
                    @if ($financialStatements)
                    <div>Financial Statements:
                        @foreach($financialStatements as $fs)
                        <div><button wire:click="previewFSinit({{ $fs->fs_id }})">{{ $fs->fs_type }}</button></div>
                            @if ($previewFS === $fs->fs_id)
                            <div>
                                <div>{{ $fs->fs_data }}</div>
                                <button wire:click="$set('previewFS', null)">Close</button>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    @else
                    <div>No associated financial statements for this collection.</div>
                    @endif
                </div>
                
                <div>
                    <!-- edit button -->
                    <button wire:click="toggleEditMode">Edit</button>
                    <!-- delete -->
                    <button wire:click="confirmDelete('{{ $fsCollection->collection_id }}')">Delete</button>
                    <!-- confirm deletion -->
                    @if ($confirming === $fsCollection->collection_id)
                    <button wire:click="deleteFinancialStatementCollection('{{ $fsCollection->collection_id }}')">Confirm Delete</button>
                    <button wire:click="$set('confirming', null)">Cancel</button>
                    @endif
                </div>
                <div>
                    {{-- export --}}
                    {{-- <button wire:click="export">Export</button>
                </div> --}}
                
            {{-- @endif
        </section> --}}
    {{-- @endif  --}}
</section>
