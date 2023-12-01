<section
    x-data="{ fs_expanded: false }"
    class="flex flex-col justify-between w-full h-full p-4 gap-2 md:w-400 md:bg-sidebar md:p-4">
    <section>
        <section class="flex items-center gap-2 mt-4 p-2 md:mt-6">
            <x-financial-reporting.assets.home-icon />
            <a class="text-white" href="/">Home</a>
        </section>

        <section class="flex items-center gap-2 mt-4 p-2 md:mt-6">
            <x-financial-reporting.assets.tb-icon />
            <a class="text-white" href="/trial-balances">Trial Balances</a>
        </section>

        <section class="flex items-center gap-2 mt-4 p-2 md:mt-6">
            <x-financial-reporting.assets.fs-icon />
            <a class="text-white" href="/financial-statements">Financial Statements</a>
        </section>
    </section>

    <section class="flex items-center gap-2 p-2 mb-4">
        <x-financial-reporting.assets.settings />
        {{-- TODO: Add modal for user settings --}}
        <button class="text-white">Settings</button>
    </section>
</section>