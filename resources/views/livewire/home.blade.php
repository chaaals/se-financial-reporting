<section x-data="{ showOverview: true, showActivity: false }" class="w-full p-2">
    <section class="p-2 mb-8">
        <h1 class="text-lg font-bold md:text-xl">Welcome back, <span class="text-primary">{{ $user->first_name }} {{ $user->last_name }}</span></h1>
        <p>we've provided a summary of the latest financial statements for you.</p>
    </section>
    <section class="w-full flex items-center justify-between mb-4">
        <section class="flex items-center gap-4">
            <button
                x-on:click="showOverview=true;showActivity=false;"
                class="w-28 p-2 rounded-full"
                :class="showOverview ? 'bg-primary text-white' : 'bg-transparent text-neutralFour'">
                Overview
            </button>
            {{-- @if($user->role == 'accounting')
            <button
                x-on:click="showOverview=false;showActivity=true;"
                class="w-28 p-2 rounded-lg"
                :class="showActivity ? 'bg-primary text-white' : 'bg-transparent text-neutralFour'">
                My Activity
            </button>
            @else
            <button
                x-on:click="showOverview=false;showActivity=true;"
                class="w-28 p-2 rounded-lg"
                :class="showActivity ? 'bg-primary text-white' : 'bg-transparent text-neutralFour'">
                Audit Trail
            </button>
            @endif --}}
        </section>
        <section class="flex items-center gap-4">
            @foreach($filterOptions as $key=>$filter)
            <select
                    class="w-20 text-xs appearance-none rounded-lg border-neutral pr-8 md:w-24 md:text-sm"
                    @if (in_array($key, ['Quarter']) && !in_array($filterPeriod, ['Quarterly']))
                        disabled
                    @endif
                    wire:model={{ $filter['model'] }}
                    wire:change='fetchChart'
                >
                        <option selected hidden>{{ $key }}</option>
                    @foreach($filter['options'] as $option)
                        <option value='{{ $option }}'>{{ $option }}</option>
                    @endforeach
            </select>
            @endforeach
        </section>
    </section>


    @if($collectionName)
    <h1 class="mb-2"><strong>{{ $collectionName }}</strong></h1>
    <section x-cloak x-show="showOverview" class="flex flex-auto gap-4">
        <section class="flex items-center justify-center shadow rounded p-4 border bg-white flex-1 h-[28rem]">
            @if($sfpo)
            <livewire:livewire-pie-chart
                key="{{$sfpoPieModel->reactiveKey()}}"
                :pie-chart-model='$sfpoPieModel'
            />
            @endif
        </section>
        <section class="flex items-center justify-center shadow rounded p-4 border bg-white flex-1 h-[28rem]">
            @if($sfpe)
            <livewire:livewire-pie-chart
                key="{{$sfpePieModel->reactiveKey()}}"
                :pie-chart-model='$sfpePieModel'
            />
            @endif
        </section>
        <section class="flex items-center justify-center shadow rounded p-4 border bg-white flex-1 h-[28rem]">
            @if($scf)
            <livewire:livewire-pie-chart
                key="{{$scfPieModel->reactiveKey()}}"
                :pie-chart-model='$scfPieModel'
            />
            @endif
        </section>
    </section>
    @else
    <section class="flex flex-col items-center">
        <x-financial-reporting.assets.no-results />
        <h1 class="text-lg">
        <strong>
        No Financial Statements found @if($filterPeriod == 'Quarterly') {{$filterQuarter}} {{$filterYear}} @else {{$filterYear}} @endif
        </strong>
        </h1>
    </section>
    @endif
</section>