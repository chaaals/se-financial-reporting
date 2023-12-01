<div>
    @if($trial_balances)
    <section>
        @foreach($trial_balances as $tb)
            <a href="/trial-balances/{{ $tb->tb_id }}">{{ $tb->tb_name }}</a>
            <div>Type: {{ $tb->tb_type }}</div>
            <div>Associated FR:</div>
            <div>Report Name: {{ $tb->financialReport->report_name }}</div>
            <div>Fiscal Year: {{ $tb->financialReport->fiscal_year }}</div>
            <div>Interim Period: {{ $tb->financialReport->interim_period }}</div>

            <div>
                <!-- delete -->
                <button wire:click="confirmDelete('{{ $tb->report_id }}')">Delete</button>
                <!-- confirm deletion -->
                @if ($confirming === $tb->report_id)
                    <button wire:click="deleteTrialBalance('{{ $tb->report_id }}')">Confirm Delete</button>
                    <button wire:click="$set('confirming', null)">Cancel</button>
                @endif
            </div>
        @endforeach
    </section>
    @endif
</div>
