<div>
    @if ($financial_report)
        <section>
            @if ($editMode)
                <div>
                    <div>
                        <label for="report_name">Report Name:</label>
                        <input id="report_name" type="text" wire:model="editedReportName">
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
                    <button wire:click="toggleEditMode">Cancel</button>
                </div>
            @else
                    <div>Report Name: {{ $financial_report->report_name }}</div>
                    <div>Start Date: {{ $financial_report->start_date }}</div>
                    <div>End Date: {{ $financial_report->end_date }}</div>
                    <div>Report Type: {{ $financial_report->report_type }}</div>
                    <div>Report Status: {{ $financial_report->report_status }}</div>
                    <div>Approved: {{ $financial_report->approved }}</div>

                    <div>
                        <!-- edit button -->
                        <button wire:click="toggleEditMode">Edit</button>
                        <!-- delete -->
                        <button wire:click="confirmDelete('{{ $financial_report->report_id }}')">Delete</button>
                        <!-- confirm deletion -->
                        @if ($confirming === $financial_report->report_id)
                            <button wire:click="deleteFinancialReport('{{ $financial_report->report_id }}')">Confirm Delete</button>
                            <button wire:click="$set('confirming', null)">Cancel</button>
                        @endif
                    </div>
            @endif
        </section>
    @endif
</div>
