<div>
    @if($financialStatement)
        <section>
            <div>Statement name: {{ $financialStatement->statement_name }}</div>
            <div>Statement type: {{ $financialStatement->statement_type }}</div>
            <div>Reference Trial Balance: {{ $financialStatement->tb_id}}</div>
            <div>Preview</div>
            <div>{{ $financialStatement->fs_data }}</div>
        </section>
    @endif
</div>
