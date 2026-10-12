<x-guest-page>
<div class="max-w-lg mx-auto bg-white rounded-2xl shadow-lg p-8">

    <div class="text-center mb-6">
        <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-7 h-7 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Track Your Order</h2>
        <p class="text-gray-500 text-sm mt-1">Enter your Order ID to check the status.</p>
    </div>

    <form method="GET" action="{{ route('orders.track') }}" class="flex gap-2 mb-6">
        <input type="text" name="order_id" placeholder="e.g. 7XQK29"
            value="{{ request('order_id') }}"
            class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 font-mono focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition"
            required>
        <button type="submit"
            class="bg-yellow-400 text-gray-900 font-semibold px-5 rounded-lg hover:bg-yellow-500 transition">
            Check
        </button>
    </form>

    @if($searched)
        @if($order)
            @php
                $statusStyles = [
                    'pending'   => ['bg-yellow-100', 'text-yellow-700', 'Your order has been received and is waiting to be printed.'],
                    'printing'  => ['bg-blue-100', 'text-blue-700', 'Your document is currently being printed.'],
                    'completed' => ['bg-purple-100', 'text-purple-700', 'Your order is ready for collection at the shop.'],
                    'collected' => ['bg-green-100', 'text-green-700', 'Your order has been collected. Thank you!'],
                ];
                [$bg, $text, $desc] = $statusStyles[$order->status];
            @endphp
            <div class="border border-gray-200 rounded-xl p-5">
                <div class="flex justify-between items-center mb-3">
                    <span class="font-mono font-semibold text-gray-700">{{ $order->order_id }}</span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $bg }} {{ $text }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 mb-2">{{ $desc }}</p>
                <p class="text-xs text-gray-400">Submitted: {{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
        @else
            <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl p-4 text-center text-sm">
                <p class="font-medium mb-1">⚠️ Order ID Not Found</p>
                <p>No order matches "<span class="font-mono">{{ request('order_id') }}</span>". Please check for typos and try again.</p>
            </div>
        @endif
    @endif

    <p class="text-center text-sm text-gray-500 mt-6">
        <a href="{{ route('home') }}" class="text-yellow-700 font-medium hover:underline">&larr; Back to Homepage</a>
    </p>

</div>
</x-guest-page>