@php
    $user = auth()->user();
    $isSupervisor = $user->role === 'supervisor';

    $menu = [
        ['label' => 'Order List', 'route' => 'staff.dashboard', 'active' => ['staff.dashboard', 'staff.show'],
         'icon' => 'M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2', 'show' => true],
        ['label' => 'Reports', 'route' => 'staff.reports', 'active' => ['staff.reports', 'staff.report.detail'],
         'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'show' => $isSupervisor],
        ['label' => 'Manage Users', 'route' => 'staff.accounts.index', 'active' => ['staff.accounts.*'],
         'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4', 'show' => $isSupervisor],
        ['label' => 'Audit Log', 'route' => 'staff.audit', 'active' => ['staff.audit'],
         'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'show' => $isSupervisor],
        ['label' => 'My Profile', 'route' => 'profile.edit', 'active' => ['profile.edit'],
         'icon' => 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'show' => true],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Snap Momen') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen bg-yellow-100 flex">

            <!-- Mobile overlay -->
            <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
                class="fixed inset-0 bg-black/40 z-30 lg:hidden"></div>

            <!-- Sidebar -->
            <aside
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed inset-y-0 left-0 z-40 w-64 bg-yellow-400 text-gray-900 flex flex-col transition-transform duration-200 ease-in-out lg:static lg:translate-x-0">

                <div class="p-6 border-b border-yellow-500">
                    <a href="{{ route('staff.dashboard') }}" class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-gray-900 rounded-lg flex items-center justify-center font-bold text-sm text-yellow-300">SM</div>
                        <div>
                            <p class="font-semibold text-sm">Snap Momen</p>
                            <p class="text-xs text-gray-700">{{ $isSupervisor ? 'Supervisor Panel' : 'Staff Panel' }}</p>
                        </div>
                    </a>
                </div>

                <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                    @foreach($menu as $item)
                        @continue(! $item['show'])
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs(...$item['active']) ? 'bg-gray-900 text-yellow-300' : 'text-gray-900 hover:bg-yellow-300' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                            </svg>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="p-4 border-t border-yellow-500">
                    <div class="px-4 py-2 mb-2">
                        <p class="text-sm font-semibold truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-700 truncate">{{ $user->email }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded-full bg-gray-900 text-yellow-300 text-[10px] font-medium uppercase tracking-wide">
                            {{ $user->role }}
                        </span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-900 hover:bg-yellow-300 transition">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Log Out
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main column -->
            <div class="flex-1 flex flex-col min-w-0">

                <!-- Mobile top bar -->
                <div class="lg:hidden flex items-center justify-between bg-yellow-400 px-4 py-3">
                    <button @click="sidebarOpen = true" class="text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <span class="font-semibold text-gray-900 text-sm">Snap Momen</span>
                    <span class="w-6"></span>
                </div>

                @isset($header)
                    <header class="bg-yellow-200 border-b border-yellow-300">
                        <div class="px-4 sm:px-6 lg:px-8 py-6">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="flex-1 overflow-x-hidden pb-10">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
