<nav class='w-screen px-8 py-4 bg-neutral-50'>
    <section class='flex items-center justify-center flex-wrap w-full md:justify-between'>
        <x-financial-reporting.assets.plm-logo />

        <div class='hidden md:flex md:items-center'>
            <x-financial-reporting.assets.user-icon />
            {{-- TODO: Add logic to get logged user --}}
            <p>User</p>
        </div>
    </section>
</nav>