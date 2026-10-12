<x-app-layout>
<div class="max-w-2xl mx-auto mt-8 px-4">

    <a href="{{ route('staff.dashboard') }}" class="text-yellow-700 text-sm font-medium hover:underline">
        &larr; Back to Dashboard
    </a>

    @php
        $statusColors = [
            'pending'   => 'bg-yellow-100 text-yellow-700',
            'printing'  => 'bg-blue-100 text-blue-700',
            'completed' => 'bg-purple-100 text-purple-700',
            'collected' => 'bg-green-100 text-green-700',
        ];
    @endphp

    <div class="bg-white rounded-xl shadow-sm p-6 mt-4">

        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 font-mono">{{ $order->order_id }}</h2>
                <p class="text-gray-400 text-sm">Submitted {{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$order->status] }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
            <div>
                <p class="text-gray-400 text-xs uppercase mb-1">Customer Name</p>
                <p class="text-gray-800 font-medium">{{ $order->customer_name }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase mb-1">Contact</p>
                <p class="text-gray-800 font-medium">{{ $order->contact_info }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase mb-1">File Type</p>
                <p class="text-gray-800 font-medium">{{ strtoupper($order->file_type) }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase mb-1">File Size</p>
                <p class="text-gray-800 font-medium">{{ round($order->file_size / 1024, 1) }} KB</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase mb-1">Page Count</p>
                <p class="text-gray-800 font-medium">{{ $order->page_count }} page(s)</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase mb-1">Copies</p>
                <p class="text-gray-800 font-medium">{{ $order->copies }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase mb-1">Print Color</p>
                <p class="text-gray-800 font-medium">{{ $order->color_mode === 'color' ? 'Full Color' : 'Black & White' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase mb-1">Orientation</p>
                <p class="text-gray-800 font-medium">{{ ucfirst($order->orientation) }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase mb-1">Paper Size</p>
                <p class="text-gray-800 font-medium">{{ $order->paper_size }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase mb-1">Payment</p>
                <p class="text-gray-800 font-medium">Pay at counter</p>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4 flex justify-between items-center">
            <span class="text-green-700 text-sm font-medium">Estimated Total</span>
            <span class="text-green-800 text-lg font-bold">RM{{ number_format($order->estimated_price, 2) }}</span>
        </div>

        @if($order->special_request)
            <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-3 mb-6 text-sm">
                <p class="text-yellow-800 font-medium text-xs uppercase mb-1">Special Request</p>
                <p class="text-yellow-800">{{ $order->special_request }}</p>
            </div>
        @endif

        @if($order->binding || $order->plastic_cover)
            <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-3 mb-6 text-sm">
                <p class="text-yellow-800 font-medium text-xs uppercase mb-1">Extra Services</p>
                <p class="text-yellow-800">
                    @if($order->binding) Binding @endif
                    @if($order->binding && $order->plastic_cover) &middot; @endif
                    @if($order->plastic_cover) Plastic Cover @endif
                </p>
            </div>
        @endif

        <div class="border-t border-gray-100 pt-5">
            @if($order->status !== 'collected')
                <div class="flex gap-3 mb-5">
                    <a href="{{ route('staff.preview', $order) }}" target="_blank"
                       class="inline-flex items-center gap-2 bg-yellow-400 text-gray-900 text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-yellow-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Preview File
                    </a>

                    <a href="{{ route('staff.print', $order) }}" target="_blank"
                       class="inline-flex items-center gap-2 bg-yellow-400 text-gray-900 text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-yellow-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a1 1 0 001-1v-4H8v4a1 1 0 001 1zm7-9V4a1 1 0 00-1-1H8a1 1 0 00-1 1v4h10z" />
                        </svg>
                        Print File
                    </a>
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-5 text-sm text-gray-500 italic">
                    File has been automatically deleted after collection.
                </div>
            @endif

            <form method="POST" action="{{ route('staff.update', $order) }}" class="flex gap-3 items-center">
                @csrf
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-500 outline-none">
                    <option value="pending" @selected($order->status == 'pending')>Pending</option>
                    <option value="printing" @selected($order->status == 'printing')>Printing</option>
                    <option value="completed" @selected($order->status == 'completed')>Completed (Ready for Collection)</option>
                    <option value="collected" @selected($order->status == 'collected')>Collected</option>
                </select>
                <button type="submit"
                    class="bg-yellow-400 text-gray-900 text-sm font-medium px-4 py-2 rounded-lg hover:bg-yellow-500 transition">
                    Update Status
                </button>
            </form>
        </div>

    </div>
</div>
</x-app-layout>