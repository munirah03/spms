<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Snap Momen') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-yellow-100" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex">

            <x-public-sidebar />

            <main class="flex-1 min-w-0 flex items-center justify-center px-4 py-16">
                <div class="w-full sm:max-w-md px-6 py-6 bg-white shadow-md rounded-lg">
                    {{ $slot }}
                </div>
            </main>

        </div>
    </body>
</html>
