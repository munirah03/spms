<x-guest-page>
<div class="max-w-3xl mx-auto text-center">

    <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome to Snap Momen</h1>
    <p class="text-gray-500 mb-10">Secure, fast, and transparent printing services — no account needed for customers.</p>

    <div class="grid md:grid-cols-2 gap-6">

        <!-- Customer Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 text-left hover:shadow-xl transition">
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-800 mb-1">I'm a Customer</h2>
            <p class="text-gray-500 text-sm mb-5">Submit a printing order or check the status of an existing one.</p>

            <div class="flex flex-col gap-2">
                <a href="{{ route('orders.create') }}"
                   class="bg-yellow-400 text-gray-900 text-center font-medium py-2.5 rounded-lg hover:bg-yellow-500 transition">
                    Submit New Order
                </a>
                <a href="{{ route('orders.track') }}"
                   class="bg-gray-100 text-gray-700 text-center font-medium py-2.5 rounded-lg hover:bg-gray-200 transition">
                    Track My Order
                </a>
            </div>
        </div>

        <!-- Staff Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 text-left hover:shadow-xl transition">
            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-800 mb-1">I'm Staff</h2>
            <p class="text-gray-500 text-sm mb-5">Log in to manage incoming orders and update printing status.</p>

            <a href="{{ route('login') }}"
               class="block bg-yellow-400 text-gray-900 text-center font-medium py-2.5 rounded-lg hover:bg-yellow-500 transition">
                Staff Login
            </a>
        </div>

    </div>
</div>
</x-guest-page>