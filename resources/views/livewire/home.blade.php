<section class="w-full p-2">
    <section class="p-2 mb-8">
        <h1 class="text-lg font-bold md:text-xl">Welcome back, <span class="text-primary">{{ $user->first_name }} {{ $user->last_name }}</span></h1>
        <p>here are recent entries of financial reports.</p>
    </section>
    <section class="w-full flex items-center justify-between mb-4">
        <div></div>
        <section class="flex items-center gap-4">
            @foreach($filterOptions as $key=>$filter)
            <select
                    class="w-20 text-xs appearance-none rounded-lg border-neutral pr-8 md:w-24 md:text-sm"
                    @if (in_array($key, ['Quarter']) && !in_array($filterPeriod, ['Quarterly']))
                        disabled
                    @endif
                    wire:model.live={{ $filter['model'] }}
                    {{-- wire:change='refreshChart' --}}
                >
                        <option selected hidden>{{ $key }}</option>
                    @foreach($filter['options'] as $option)
                        <option value='{{ $option }}'>{{ $option }}</option>
                    @endforeach
            </select>
            @endforeach
                {{-- <button wire:click='refreshFilters'>
                    <x-financial-reporting.assets.refresh />
                </button> --}}
        </section>
    </section>
    <section class="flex gap-4">
        <section class="w-full bg-white drop-shadow-md rounded-lg p-4">
            {!! $sfpoPie->container() !!}
        </section>
        <section class="w-full bg-white drop-shadow-md rounded-lg p-4"">
            {!! $sfpePie->container() !!}
        </section>
        <section class="w-full bg-white drop-shadow-md rounded-lg p-4"">
            {!! $scfPie->container() !!}
        </section>
    </section>
</section>
<script src="{{ $sfpoPie->cdn() }}"></script>
<script src="{{ $sfpePie->cdn() }}"></script>
<script src="{{ $scfPie->cdn() }}"></script>


{{ $sfpoPie->script() }}
{{ $sfpePie->script() }}
{{ $scfPie->script() }}