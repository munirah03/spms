<x-app-layout>
<div class="max-w-5xl mx-auto mt-8 px-4">

    <a href="{{ route('staff.reports') }}" class="text-yellow-700 text-sm font-medium hover:underline">
        &larr; Back to Reports
    </a>

    @php
        $statusColors = [
            'pending'   => 'bg-yellow-100 text-yellow-700',
            'printing'  => 'bg-blue-100 text-blue-700',
            'completed' => 'bg-purple-100 text-purple-700',
            'collected' => 'bg-green-100 text-green-700',
        ];
    @endphp

    <div class="mt-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</h2>
        <p class="text-gray-500 text-sm">{{ $report->total_orders }} orders — Total: RM{{ number_format($report->total_estimated_revenue, 2) }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="w-full min-w-[640px]">
            <thead>
                <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wide">
                    <th class="p-4">Order ID</th>
                    <th class="p-4">Customer</th>
                    <th class="p-4">File</th>
                    <th class="p-4">Copies</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Price</th>
                    <th class="p-4">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $o)
                <tr>
                    <td class="p-4 font-mono text-sm text-gray-700">{{ $o->order_id }}</td>
                    <td class="p-4 text-sm text-gray-700">{{ $o->customer_name }}</td>
                    <td class="p-4 text-sm text-gray-500">{{ $o->file_original_name }}</td>
                    <td class="p-4 text-sm text-gray-500">{{ $o->copies }}</td>
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$o->status] ?? '' }}">
                            {{ ucfirst($o->status) }}
                        </span>
                    </td>
                    <td class="p-4 text-sm font-medium text-gray-800">RM{{ number_format($o->estimated_price, 2) }}</td>
                    <td class="p-4 text-sm text-gray-500">{{ $o->original_created_at ? \Carbon\Carbon::parse($o->original_created_at)->format('h:i A') : '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="p-8 text-center text-gray-400">No orders recorded for this day.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
</x-app-layout>