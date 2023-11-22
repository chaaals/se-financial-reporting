<div>
    @if($financial_reports)
    <section>
        @foreach($financial_reports as $fr)
            @if ($editMode && $editedReportID === $fr->report_id)
                <div>
                    <label for="report_name">Report Name:</label>
                    <input id="report_name" type="text" wire:model="editedReportName">
                </div>
                <div>
                    <label for="tb_ids">Trial Balance:</label>
                    @if ($trial_balances)
                        <select id="tb_ids" wire:model="editedTBID">
                            @foreach ($trial_balances as $tb)
                                <option value="{{ $tb->tb_id }}">{{ $tb->tb_name }}</option>
                            @endforeach
                        </select>
                    @else
                        <input id="tb_ids" type="text" value="No Trial Balance Available" disabled>
                    @endif
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
                <button wire:click="updateFinancialReport">Save</button>
                <button wire:click="$set('editMode', false)">Cancel</button>
            @else
                <a href="/financial-reports/{{ $fr->report_id }}">{{ $fr->report_name }}</a>
                <div>{{ $fr->period }}</div>
                <div>{{ $fr->start_date }}</div>
                <div>{{ $fr->end_date }}</div>
                <div>{{ $fr->report_type }}</div>
                <div>{{ $fr->report_status }}</div>
                <div>{{ $fr->approved }}</div>
                <div>{{ $fr->tb_id }}</div>
                <div>
                    <!-- Edit button -->
                    <button wire:click="toggleEditMode('{{ $fr->report_id }}')">Edit</button>
                    <!-- delete -->
                    <button wire:click="confirmDelete('{{ $fr->report_id }}')">Delete</button>
                    <!-- confirm deletion -->
                    @if ($confirming === $fr->report_id)
                        <button wire:click="deleteFinancialReport('{{ $fr->report_id }}')">Confirm Delete</button>
                        <button wire:click="$set('confirming', null)">Cancel</button>
                    @endif
                </div>
            @endif
        @endforeach
    </section>
    @endif
</div>
