<div>
    @if($fsCollections)
        @foreach($fsCollections as $fsc)
            <div>Collection name: <a href="/financial-statements/{{ $fsc->collection_id }}">{{ $fsc->collection_name }}</a></div>
            <div>Status: {{ $fsc->collection_status }}</div>
            <div>Date: {{ $fsc->date }}</div>
            @if ($fsc->quarter)
                <div>Quarter: {{ $fsc->quarter }}</div>
            @endif
            <div>Interim Period: {{ $fsc->interim_period }}</div>
            <div>
            @if ($fsc->approved)
                Approved
            @else
                Not Approved
            @endif
            </div>
            <div>
            @php
                $fsINIT = $this->getFSinit($fsc->collection_id);
            @endphp
            @if ($fsINIT->isNotEmpty())
                <div>Financial Statements:
                @foreach($fsINIT as $fs)
                    {{ $fs->fs_type }}
                @endforeach
                </div>
            @else
                <div>No associated financial statements for this collection.</div>
            @endif
            </div>

            <div>
                <!-- delete -->
                <button wire:click="confirmDelete('{{ $fsc->collection_id }}')">Delete</button>
                <!-- confirm deletion -->
                @if ($confirming === $fsc->collection_id)
                    <button wire:click="deleteFinancialStatementCollection('{{ $fsc->collection_id }}')">Confirm Delete</button>
                    <button wire:click="$set('confirming', null)">Cancel</button>
                @endif
            </div>
        @endforeach

    @else
        <div>No available financial statement</div>
    @endif
</div>
