<?php

use Illuminate\Support\Facades\Route;

$base = "flex items-center gap-2 mt-4 p-2 md:mt-6";

$styles = [
    "/" => $base,
    "/trial-balances" => $base,
    "/financial-statements" => $base,
    "/financial-reports" => $base
];

$route = Route::current()->getPrefix();

$styles[$route] = $base . " bg-active";
?>


<section
    x-data="{ fs_expanded: false }"
    class="flex flex-col justify-between w-full h-full p-4 gap-2 md:w-400 md:bg-primary md:p-4">
    <section>
        <section class="{{ $styles["/"] }}">
            <x-financial-reporting.assets.home-icon />
            <a class="text-white" href="/">Home</a>
        </section>

        <section class="{{ $styles["/trial-balances"] }}">
            <x-financial-reporting.assets.tb-icon />
            <a class="text-white" href="/trial-balances">Trial Balance</a>
        </section>

        <section class="{{ $styles["/financial-statements"] }}">
            <x-financial-reporting.assets.fs-icon />
            <a class="text-white" href="/financial-statements">Financial Statements</a>
        </section>

        <section class="{{ $styles["/financial-reports"] }}">
            <x-financial-reporting.assets.fr-icon />
            <a class="text-white" href="/financial-reports">Financial Reports</a>
        </section>
    </section>

    <section class="flex items-center gap-2 p-2 mb-4">
        <x-financial-reporting.assets.settings />
        {{-- TODO: Add modal for user settings --}}
        <button class="text-white">Settings</button>
    </section>
</section>