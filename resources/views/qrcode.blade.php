<x-guest-page>
<div class="max-w-md mx-auto bg-white rounded-2xl shadow-lg p-8 text-center">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Scan to Place Printing Order</h2>

    <div class="flex justify-center mb-4">
        {!! QrCode::size(220)->generate($url) !!}
    </div>

    <p class="text-gray-500 text-sm">{{ $url }}</p>
</div>
</x-guest-page>