<div>
    @if($trial_balances)
    <section>
        @foreach($trial_balances as $tb)
            <a href="/trial-balances/{{ $tb->tb_id }}">{{ $tb->report_name }}</a>
            @if ($tb->tb_type)
                <div>Type: {{ $tb->tb_type }}</div>
            @endif
            <div>Status: {{ $tb->report_status }}</div>
            <div>Date: {{ $tb->date }}</div>
            @if ($tb->quarter)
                <div>Quarter: {{ $tb->quarter }}</div>
            @endif
            <div>Interim Period: {{ $tb->interim_period }}</div>

            <div>
                <!-- delete -->
                <button wire:click="confirmDelete('{{ $tb->tb_id }}')">Delete</button>
                <!-- confirm deletion -->
                @if ($confirming === $tb->tb_id)
                    <button wire:click="deleteTrialBalance('{{ $tb->tb_id }}')">Confirm Delete</button>
                    <button wire:click="$set('confirming', null)">Cancel</button>
                @endif
            </div>
        @endforeach
    </section>
    @endif
</div>
