<div>
    @if(!$financialStatements->isEmpty())
        @foreach($financialStatements as $fs)
            <div>Statement name: <a href="/financial-statements/{{ $fs->statement_id }}">{{ $fs->statement_name }}</a></div>
            <div>Statement type: {{ $fs->statement_type }}</div>
        @endforeach

    @else
        <div>No available financial statement type : {{ $type }}</div>
    @endif
</div>
