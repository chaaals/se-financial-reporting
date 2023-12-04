<div>
    @if ($fsCollection)
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
                <div>
                    @if ($financialStatements)
                    <div>Financial Statements:
                        @foreach($financialStatements as $fs)
                        <div><a href="/financial-statements/{{ $fsCollection->collection_id }}/{{ $fs->fs_id }}">{{ $fs->fs_type }}</a></div>
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
                    <button wire:click="export">Export</button>
                </div>
                
            @endif
        </section>
    @endif
</div>
