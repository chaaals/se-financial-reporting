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
    class="w-full h-full p-4 gap-2 md:w-400 md:h-screen md:bg-sidebar md:p-4">
    <section class="{{ $styles["/"] }}">
        <x-financial-reporting.assets.home-icon />
        <a class="text-white" href="/">Home</a>
    </section>

    <section class="{{ $styles["/trial-balances"] }}">
        <x-financial-reporting.assets.tb-icon />
        <a class="text-white" href="/trial-balances">Trial Balance</a>
    </section>

    <section class="flex flex-col gap-2">
        <section class="{{ $styles["/financial-statements"] }}">
            <x-financial-reporting.assets.fs-icon />
            <a class="text-white" href="/financial-statements">Financial Statements</a>
            <button x-show="! fs_expanded" x-on:click="fs_expanded = true">
                <x-financial-reporting.assets.chevron-down />
            </button>
            <button x-cloak x-show="fs_expanded" x-on:click="fs_expanded = false">
                <x-financial-reporting.assets.chevron-up />
            </button>
        </section>

        <ul class="pl-8" x-cloak x-transition x-show="fs_expanded">
            <li class="mt-4 overflow-hidden">
                <a href="/financial-statements?type=SFPO" class="text-white text-sm">Financial Position</a>
            </li>
            <li class="mt-8 overflow-hidden">
                <a href="/financial-statements?type=SFPE" class="text-white text-sm">Financial Performance</a>
            </li>
            <li class="mt-8 overflow-hidden">
                <a href="/financial-statements?type=SCNAE" class="text-white text-sm">Net Assets/Equity</a>
            </li>
            <li class="mt-8 overflow-hidden">
                <a href="/financial-statements?type=SCF" class="text-white text-sm">Cash Flow</a>
            </li>
            <li class="mt-8 overflow-hidden">
                <a href="/financial-statements?type=SCBAA" class="text-white text-sm">Budget and Actual Amounts</a>
            </li>
        </ul>
    </section>

    <section class="{{ $styles["/financial-reports"] }}">
        <x-financial-reporting.assets.fr-icon />
        <a class="text-white" href="/financial-reports">Financial Reports</a>
    </section>
</section>