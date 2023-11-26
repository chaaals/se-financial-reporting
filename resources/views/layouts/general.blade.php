<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $pageTitle }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>

    <body class="relative w-screen h-screen overflow-x-hidden overflow-y-scroll">
        <x-financial-reporting.navbar />

        <main class="flex w-full h-full">
            <section class="hidden md:block md:h-full">
                <x-financial-reporting.sidebar />
            </section>
            <section class="grow">{{ $slot }}</section>
        </main>

        @livewireScripts
    </body>
</html>