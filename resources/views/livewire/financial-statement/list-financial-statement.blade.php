<div>
    @if(!$financialStatements->isEmpty())
        @foreach($financialStatements as $fs)
            <div>Statement name: <a href="/financial-statements/{{ $fs->statement_id }}">{{ $fs->report_name }}</a></div>
            <div>Statement type: {{ $fs->fs_type }}</div>
            <div>Status: {{ $fs->report_status }}</div>
            <div>Date: {{ $fs->date }}</div>
            @if ($fs->quarter)
                <div>Quarter: {{ $fs->quarter }}</div>
            @endif
            <div>Interim Period: {{ $fs->interim_period }}</div>

            <div>
                <!-- delete -->
                <button wire:click="confirmDelete('{{ $fs->statement_id }}')">Delete</button>
                <!-- confirm deletion -->
                @if ($confirming === $fs->statement_id)
                    <button wire:click="deleteFinancialStatement('{{ $fs->statement_id }}')">Confirm Delete</button>
                    <button wire:click="$set('confirming', null)">Cancel</button>
                @endif
            </div>
        @endforeach

    @else
        <div>No available financial statement type : {{ $type }}</div>
    @endif
</div>
