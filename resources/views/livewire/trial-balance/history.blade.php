<section
    class="relative w-10 h-10 flex items-center justify-center hidden md:block"
    x-data="{ isHistoryVisible: false }"
    x-on:click.outside="isHistoryVisible = false">
    <section class="w-full h-full flex items-center justify-center">
        <button class="relative" x-on:click="isHistoryVisible = true">
            <x-financial-reporting.assets.history />
        </button>
    </section>

    <section
        x-cloak
        x-show="isHistoryVisible"
        class="absolute top-0 right-0 w-96 bg-white custom-dropshadow z-10 rounded-lg md:flex md:flex-col"
    >
    
    </section>
</section>