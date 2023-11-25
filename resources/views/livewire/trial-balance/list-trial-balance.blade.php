<div>
    @if($trial_balances)
    <section>
        @foreach($trial_balances as $tb)
            <a href="/trial-balances/{{ $tb->tb_id }}">{{ $tb->tb_name }}</a>
            <div>{{ $tb->period }}</div>
            <div>{{ $tb->closing }}</div>
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
