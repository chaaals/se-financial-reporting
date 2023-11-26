<div>
    @if($financialStatement)
        <section>
            <div>Statement id: {{ $financialStatement->statement_id }}</div>
            <div>Statement name: {{ $financialStatement->statement_name }}</div>
            <div>Statement type: {{ $financialStatement->statement_type }}</div>
            <div>Reference Trial Balance: {{ $financialStatement->tb_id}}</div>
            <div>Preview</div>
            <div>{{ $financialStatement->fs_data }}</div>
            <div>
                        <!-- delete -->
                        <button wire:click="confirmDelete('{{ $financialStatement->statement_id }}')">Delete</button>
                        <!-- confirm deletion -->
                        @if ($confirming === $financialStatement->statement_id)
                            <button wire:click="deleteFinancialStatement('{{ $financialStatement->statement_id }}')">Confirm Delete</button>
                            <button wire:click="$set('confirming', null)">Cancel</button>
                        @endif
                    </div>
        </section>
    @endif
</div>
