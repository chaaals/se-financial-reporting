<div>
    @if ($trial_balance)
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
                    <button wire:click="export">Export</button>
                </div>
            @endif
        </section>
    @endif
</div>
