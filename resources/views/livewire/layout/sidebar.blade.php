<section
    class="flex flex-col justify-between w-full h-full p-4 gap-2 md:w-400 md:bg-primary md:p-4">
    <section>
        <section class="{{ $styles['/'] }}">
            <x-financial-reporting.assets.home-icon />
            <button class="text-white" wire:click="goto('/')">Home</button>
        </section>

        <section class="{{ $styles['/trial-balances'] }}">
            <x-financial-reporting.assets.tb-icon />
            <button class="text-white" wire:click="goto('/trial-balances')">Trial Balances</button>
        </section>

        <section class="{{ $styles['/financial-statements'] }}">
            <x-financial-reporting.assets.fs-icon />
            <button class="text-white" wire:click="goto('/financial-statements')">Financial Statements</button>
        </section>
    </section>

    {{-- <section class="flex items-center gap-2 p-2 mb-4"> --}}
        {{-- <x-financial-reporting.assets.settings /> --}}
        {{-- TODO: Add modal for user settings --}}
        {{-- <button class="text-white">Settings</button> --}}
    {{-- </section> --}}
</section>