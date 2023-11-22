<div>
    @if ($financial_report)
        <section>
            <div>{{ $financial_report->report_name }}</div>
            <div>{{ $financial_report->period }}</div>
            <div>{{ $financial_report->start_date }}</div>
            <div>{{ $financial_report->end_date }}</div>
            <div>{{ $financial_report->report_type }}</div>
            <div>{{ $financial_report->report_status }}</div>
            <div>{{ $financial_report->approved }}</div>
            <div>{{ $financial_report->tb_id }}</div>

            <div>
                <!-- delete -->
                <button wire:click="confirmDelete('{{ $financial_report->report_id }}')">Delete</button>
                <!-- confirm deletion -->
                @if ($confirming === $financial_report->report_id)
                    <button wire:click="deleteFinancialReport('{{ $financial_report->report_id }}')">Confirm Delete</button>
                    <button wire:click="$set('confirming', null)">Cancel</button>
                @endif
            </div>
        </section>
    @endif
</div>
