<?php

use App\Livewire\Actions\Logout;

$logout = function (Logout $logout) {
    $logout();

    $this->redirect('/', navigate: true);
};
?>


<nav class="w-full px-8 py-4 bg-neutral-50">
    <section x-data="{ open: false }" class="flex items-center justify-between flex-wrap w-full">
        <x-financial-reporting.assets.plm-logo />

        <section class="hidden md:flex md:items-center">
            <x-financial-reporting.assets.user-icon />
            {{-- TODO: Add logic to get logged user --}}
            <button wire:click='logout'>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</button>
        </section>

        <button class="md:hidden" x-on:click="open = true" x-show="! open">
            <x-financial-reporting.assets.hamburger-menu />
        </button>

        <section
            class="fixed top-0 left-0 w-screen h-screen bg-primary py-2 flex flex-col z-10 md:hidden"
            x-show="open"
            x-cloak
        >
            <button class="absolute top-8 right-8 z-10 md:hidden" x-cloak x-show="open" x-on:click="open = false">
                <x-financial-reporting.assets.x class="z-10" />
            </button>

            <livewire:layout.sidebar />
        </section>
    </section>
</nav>