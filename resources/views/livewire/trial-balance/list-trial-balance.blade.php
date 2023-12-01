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
            <th class="relative text-left px-4 py-2">
                Report name
                <button class="absolute top-1 right-2">
                    <x-financial-reporting.assets.table-sort />
                </button>
            </th>
            <th class="relative hidden md:table-cell md:text-left p-2">
                Report type
                <button class="absolute top-1 right-2">
                    <x-financial-reporting.assets.table-sort />
                </button>
            </th>
            <th class="relative hidden text-left md:table-cell p-2">
                Interim period
                <button class="absolute top-1 right-2">
                    <x-financial-reporting.assets.table-sort />
                </button>
            </th>
            <th class="relative hidden text-left md:table-cell p-2">
                Date
                <button class="absolute top-1 right-2">
                    <x-financial-reporting.assets.table-sort />
                </button>
            </th>
            <th class="relative text-left p-2">
                Report status
                <button class="absolute top-1 right-2">
                    <x-financial-reporting.assets.table-sort />
                </button>
            </th>
        </thead>

        <tbody>
        
        </tbody>
    </table>
</section>
