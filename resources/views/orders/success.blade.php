<x-guest-page>
<div class="max-w-lg mx-auto bg-white rounded-2xl shadow-lg p-8 text-center">

    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </div>

    <h2 class="text-2xl font-bold text-gray-800 mb-1">Order Submitted!</h2>
    <p class="text-gray-500 text-sm mb-6">Your printing request has been received.</p>

    <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 mb-6">
        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Your Order ID</p>
        <p class="text-3xl font-mono font-bold text-yellow-700 tracking-wider mb-4">{{ $orderId }}</p>

        <div class="border-t border-gray-200 pt-3 flex justify-between text-sm">
            <span class="text-gray-500">Estimated Total</span>
            <span class="font-semibold text-gray-800">RM{{ number_format($price ?? 0, 2) }}</span>
        </div>
        <div class="flex justify-between text-sm mt-1">
            <span class="text-gray-500">Payment</span>
            <span class="font-semibold text-gray-800">Pay at counter</span>
        </div>
    </div>
    <p class="text-xs text-gray-400 -mt-4 mb-6">*Estimated price — pay at counter, final amount confirmed by staff.</p>

    <p class="text-sm text-gray-500 mb-6">
        Save this ID — you'll need it to check your order status later. No account or login required.
    </p>

    @if (filter_var(session('last_contact_info'), FILTER_VALIDATE_EMAIL))
    <p class="text-xs text-gray-500 mb-6">We've emailed your Order ID. If you can't see it, please check your Spam folder.</p>
@endif

    @php
    $waDigits = preg_replace('/\D+/', '', (string) session('last_contact_info'));
    if (str_starts_with($waDigits, '0')) { $waDigits = '6' . $waDigits; }
    $waText = rawurlencode("Order ID Snap Momen saya: {$orderId}. Semak status di halaman Track Order.");
@endphp
@if (preg_match('/^601\d{8,9}$/', $waDigits))
<div class="mb-6">
    <a href="https://wa.me/{{ $waDigits }}?text={{ $waText }}" target="_blank" rel="noopener"
       class="inline-flex items-center gap-2 bg-[#25D366] text-white font-semibold px-5 py-2.5 rounded-lg hover:opacity-90 transition">
        Save the Order ID to WhatsApp
    </a>
    <p class="text-xs text-gray-400 mt-2">Press once, Order ID will be saved to your WhatsApp chat.</p>
</div>
@endif

    <div class="flex gap-3 justify-center">
        <a href="{{ route('orders.track') }}"
           class="bg-yellow-400 text-gray-900 font-medium px-5 py-2.5 rounded-lg hover:bg-yellow-500 transition">
            Track My Order
        </a>
        <a href="{{ route('orders.create') }}"
           class="bg-gray-100 text-gray-700 font-medium px-5 py-2.5 rounded-lg hover:bg-gray-200 transition">
            Submit Another
        </a>
    </div>
</div>
</x-guest-page>