@php
    $links = [
        ['label' => 'Home', 'href' => route('home'), 'on' => request()->routeIs('home'),
         'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['label' => 'Submit Order', 'href' => route('orders.create'), 'on' => request()->routeIs('orders.create', 'orders.success'),
         'icon' => 'M12 4v16m8-8H4'],
        ['label' => 'Track Order', 'href' => route('orders.track'), 'on' => request()->routeIs('orders.track'),
         'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
        ['label' => auth()->check() ? 'Staff Dashboard' : 'Staff Login',
         'href' => auth()->check() ? route('staff.dashboard') : route('login'),
         'on' => request()->routeIs('login', 'password.*'),
         'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
    ];
@endphp

<!-- Mobile menu button -->
<button @click="sidebarOpen = true" class="lg:hidden fixed top-3 left-3 z-20 p-2 rounded-lg bg-yellow-400 text-gray-900 shadow">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>

<!-- Mobile overlay -->
<div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-black/40 z-30 lg:hidden"></div>

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-yellow-400 text-gray-900 flex flex-col transition-transform duration-200 ease-in-out lg:static lg:translate-x-0">

    <div class="p-6 border-b border-yellow-500">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="w-9 h-9 bg-gray-900 rounded-lg flex items-center justify-center font-bold text-sm text-yellow-300">SM</div>
            <div>
                <p class="font-semibold text-sm">Snap Momen</p>
                <p class="text-xs text-gray-700">Secure Print Management</p>
            </div>
        </a>
    </div>

    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        @foreach($links as $item)
            <a href="{{ $item['href'] }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                      {{ $item['on'] ? 'bg-gray-900 text-yellow-300' : 'text-gray-900 hover:bg-yellow-300' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                </svg>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="p-4 border-t border-yellow-500 text-xs text-gray-700 px-8">
        &copy; {{ date('Y') }} Snap Momen
    </div>
</aside>
