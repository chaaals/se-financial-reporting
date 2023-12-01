<div>
    @if ($financialStatement)
        <section>
            @if ($editMode)
                <div>
                    <div>
                        <label for="report_name">Report Name:</label>
                        <input id="report_name" type="text" wire:model="editedReportName" placeholder="optional">
                    </div>
                    <div>
                        <label for="fs_type">Type:</label>
                        <select id="fs_type" wire:model="editedFSType">
                            <option value="SFPO">SFPO</option>
                            <option value="SFPE">SFPE</option>
                            <option value="SCF">SCF</option>
                        </select>
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
                    <button wire:click="updateFinancialStatement">Save</button>
                    <button wire:click="toggleEditMode">Cancel</button>
                </div>
            @else
                <div>Name: {{ $financialStatement->report_name }}</div>
                @if ($financialStatement->fs_type)
                    <div>Type: {{ $financialStatement->fs_type }} (using template {{ $financialStatement->template_name}})</div>
                @endif
                <div>Status: {{ $financialStatement->report_status }}</div>
                <div>Date: {{ $financialStatement->date }}</div>
                @if ($financialStatement->quarter)
                    <div>Quarter: {{ $financialStatement->quarter }}</div>
                @endif
                <div>Interim Period: {{ $financialStatement->interim_period }}</div>

                <div>
                    <!-- edit button -->
                    <button wire:click="toggleEditMode">Edit</button>
                    <!-- delete -->
                    <button wire:click="confirmDelete('{{ $financialStatement->statement_id }}')">Delete</button>
                    <!-- confirm deletion -->
                    @if ($confirming === $financialStatement->statement_id)
                        <button wire:click="deleteFinancialStatement('{{ $financialStatement->statement_id }}')">Confirm Delete</button>
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
