<?php

use Illuminate\Support\Facades\Auth;
use App\Livewire\Actions\Logout;

$logout = function (Logout $logout) {
    $env = env('APP_ENV');

    if($env == 'local'){
        $logout();
        $this->redirect('/', navigate: true);
    } else {
        if(Auth::check()){
            $userType = session('usertype');
            $this->redirect("https://login.plmerp24.cloud/$userType"."dashboard", navigate: true);
        }
    }
};
?>


<nav class="w-full px-8 py-4 bg-neutral-50">
    <section x-data="{ open: false }" class="flex items-center justify-between flex-wrap w-full">
        <x-financial-reporting.assets.plm-logo />

        <section class="hidden md:flex md:items-center">
            <x-financial-reporting.assets.user-icon />
            {{-- TODO: Add logic to get logged user --}}
            <div x-data="{ open: false }" @click.away="open = false" style="z-index: 999;">
                <button @click="open = !open" class="flex items-center space-x-2">
                    <span>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                    <x-financial-reporting.assets.chevron-down />
                </button>
                <ul x-cloak x-show="open" class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg">
                    <li>
                        <button wire:click="logout" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-200 focus:outline-none">Go to Dashboard</button>
                    </li>
                </ul>
            </div>
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