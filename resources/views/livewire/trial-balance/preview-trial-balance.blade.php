<div>
    @if ($trial_balance)
        <section>
            <div>{{ $trial_balance->tb_name }}</div>
            <div>{{ $trial_balance->period }}</div>

            <div>
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
        </section>
    @endif
</div>
