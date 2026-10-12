<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Secure Print Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-yellow-100 min-h-screen" x-data="{ sidebarOpen: false }">
<div class="min-h-screen flex">

    <x-public-sidebar />

    <div class="flex-1 flex flex-col min-w-0">
        <main class="flex-1 pt-16 pb-10 lg:pt-10 px-4">
            {{ $slot }}
        </main>
        <footer class="text-center text-xs text-gray-600 py-6">
            &copy; {{ date('Y') }} Snap Momen. All rights reserved.
        </footer>
    </div>

</div>
</body>
</html>
