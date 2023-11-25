<nav class="w-full px-8 py-4 bg-neutral-50">
    <section x-data="{ open: false }" class="flex items-center justify-between flex-wrap w-full">
        <x-financial-reporting.assets.plm-logo />

        <section class="hidden md:flex md:items-center">
            <x-financial-reporting.assets.user-icon />
            {{-- TODO: Add logic to get logged user --}}
            <p>User</p>
        </section>

        <button class="md:hidden" x-on:click="open = true" x-show="! open">
            <x-financial-reporting.assets.hamburger-menu />
        </button>

        {{-- <button class="z-10 md:hidden" x-cloak x-show="open" x-on:click="open = false">
            <x-financial-reporting.assets.x class="z-10" />
        </button> --}}

        <section
            class="fixed top-0 left-0 w-screen h-screen bg-sidebar py-2 flex flex-col md:hidden"
            x-show="open"
            x-cloak
        >
            <button class="absolute top-8 right-8 z-10 md:hidden" x-cloak x-show="open" x-on:click="open = false">
                <x-financial-reporting.assets.x class="z-10" />
            </button>
            <div>
                <x-financial-reporting.sidebar />
            </div>

            <div class="flex items-center ml-2">
                <x-financial-reporting.assets.wuser-icon />
                    {{-- TODO: Add logic to get logged user --}}
                <p class="text-white">User</p>
            </div>
        </section>
    </section>
</nav>