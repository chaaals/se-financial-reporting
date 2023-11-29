<div>
    @if ($financial_report)
        <section>
            @if ($editMode)
                <div>
                    <div>
                        <label for="report_name">Report Name:</label>
                        <input id="report_name" type="text" wire:model="editedReportName" placeholder="optional">
                    </div>
                    <div>
                        <label for="fiscal_year">Fiscal Year:</label>
                        <select id="fiscal_year" wire:model="editedFiscalYear">
                            @foreach ($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
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
                    <button wire:click="toggleEditMode">Cancel</button>
                </div>
            @else
                    <div>Report Name: {{ $financial_report->report_name }}</div>
                    <div>Fiscal Year: {{ $financial_report->fiscal_year }}</div>
                    <div>Interim Period: {{ $financial_report->interim_period }}</div>
                    <div>Quarter: {{ $financial_report->quarter }}</div>
                    <div>Report Status: {{ $financial_report->report_status }}</div>
                    <div>Approved: {{ $financial_report->approved }}</div>
                    <div>tb_id: {{ $financial_report->tb_id }}</div>

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
