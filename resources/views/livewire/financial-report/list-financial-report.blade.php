<div>
    @if($financial_reports)
    <section>
        @foreach($financial_reports as $fr)
            <a href="/financial-reports/{{ $fr->report_id }}">Report Name: {{ $fr->report_name }}</a>
            <div>Fiscal Year: {{ $fr->fiscal_year }}</div>
            <div>Interim Period: {{ $fr->interim_period }}</div>
            <div>Quarter: {{ $fr->quarter }}</div>
            <div>Report Status: {{ $fr->report_status }}</div>
            <div>Approved: {{ $fr->approved }}</div>
            <div>tb_id: {{ $fr->tb_id }}</div>
            <div>
                <!-- delete -->
                <button wire:click="confirmDelete('{{ $fr->report_id }}')">Delete</button>
                <!-- confirm deletion -->
                @if ($confirming === $fr->report_id)
                <button wire:click="deleteFinancialReport('{{ $fr->report_id }}')">Confirm Delete</button>
                <button wire:click="$set('confirming', null)">Cancel</button>
                @endif
            </div>
        @endforeach
    </section>
    @endif
</div>
