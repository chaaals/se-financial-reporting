<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    @if(!$financialStatements->isEmpty())
        @foreach($financialStatements as $fs)
            <div>{{ $fs->statement_type }}</div>
            <div>{{ $fs->fs_data }}</div>
        @endforeach

    @else
        <div>No available financial statement type : {{ $type }}</div>
    @endif
</div>
