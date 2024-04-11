<section class="p-2">
    <section class="p-2 mb-8">
        <h1 class="text-lg font-bold md:text-xl">Welcome back, <span class="text-primary">{{ $user->first_name }} {{ $user->last_name }}</span></h1>
        <p>here are recent entries of financial reports.</p>
    </section>
    <section class="flex flex-auto gap-12">
        <section class="w-full">
            <section class="w-full flex items-center justify-between p-2">
                <h2 class="text-lg font-bold">Recent Trial Balances</h2>
                
                <div class="flex items-center gap-2">
                    <a class="text-md" href="/trial-balances">See All
                    </a>
                    <x-financial-reporting.assets.chevrons-right />
                </div>
            </section>

            <section class="w-full flex flex-wrap gap-4 p-2 mt-2">
                    @foreach($trialBalances as $i=>$data)
                        <x-financial-reporting.tb-bento-tile
                        :data="$data"
                        wire:click="bentoRedirect('trial-balances', '{{$data->tb_id}}')"
                        />
                    @endforeach
            </section>
        </section>
        <section class="w-full">
            <section class="w-full flex items-center justify-between p-2">
                <h2 class="text-lg font-bold">Recent Financial Statements</h2>
                
                <div class="flex items-center gap-2">
                    <a class="text-md" href="/financial-statements">See All
                    </a>
                    <x-financial-reporting.assets.chevrons-right />
                </div>
            </section>

            <section class="w-full flex flex-wrap gap-4 p-2 mt-2">
                    @foreach($financialStatements as $i=>$data)
                        <x-financial-reporting.fs-bento-tile
                        :data="$data"
                        wire:click="bentoRedirect('financial-statements', '{{$data->collection_id}}')"
                        />
                    @endforeach
            </section>
        </section>
    </section>
</section>