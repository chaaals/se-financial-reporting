<section class="w-full p-4">
    {{-- @if($trial_balances)
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
    @endif --}}

    <table class="w-full table-auto rounded-t-lg overflow-hidden">
        <thead class="bg-primary text-white">
            <th class="text-left p-2">Report name</th>
            <th class="hidden md:table-cell md:text-left p-2">Fiscal year</th>
            <th class="hidden text-left md:table-cell p-2">Interim period</th>
            <th class="hidden text-left md:table-cell p-2">Date</th>
            <th class="text-left p-2">Report status</th>
        </thead>

        <tbody>
            
        </tbody>
    </table>
</section>
