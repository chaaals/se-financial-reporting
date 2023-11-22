<div>
    @if($financial_reports)
    <section>
        @foreach($financial_reports as $fr)
            <a href="/financial-reports/{{ $fr->report_id }}">{{ $fr->report_name }}</a>
            <div>{{ $fr->period }}</div>
            <div>{{ $fr->start_date }}</div>
            <div>{{ $fr->end_date }}</div>
            <div>{{ $fr->report_type }}</div>
            <div>{{ $fr->report_status }}</div>
            <div>{{ $fr->approved }}</div>
            <div>{{ $fr->tb_id }}</div>
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
