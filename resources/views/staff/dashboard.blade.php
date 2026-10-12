<x-app-layout>
<div class="max-w-6xl mx-auto mt-8 px-4">

    <div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Order Dashboard</h2>
        <p class="text-gray-500 text-sm">Manage all incoming printing orders</p>
    </div>
    <div class="text-sm text-gray-500 bg-white px-4 py-2 rounded-lg shadow-sm">
        Logged in as <span class="font-medium text-gray-700">{{ auth()->user()->name }}</span>
    </div>
</div>

<form method="GET" action="{{ route('staff.dashboard') }}" class="mb-6">
    <div class="flex gap-2">
        <input type="text" name="search" value="{{ $search ?? '' }}"
            placeholder="Search by Order ID, name, or contact..."
            class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition">
        <button type="submit" class="bg-yellow-400 text-gray-900 font-medium px-5 rounded-lg hover:bg-yellow-500 transition">
            Search
        </button>
        @if(!empty($search))
            <a href="{{ route('staff.dashboard') }}" class="bg-gray-100 text-gray-600 font-medium px-4 py-2.5 rounded-lg hover:bg-gray-200 transition">
                Clear
            </a>
        @endif
    </div>
</form>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-5 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @php
        $statusColors = [
            'pending'   => 'bg-yellow-100 text-yellow-700',
            'printing'  => 'bg-blue-100 text-blue-700',
            'completed' => 'bg-purple-100 text-purple-700',
            'collected' => 'bg-green-100 text-green-700',
        ];
        $pendingCount = $orders->where('status', 'pending')->count();
        $printingCount = $orders->where('status', 'printing')->count();
        $completedCount = $orders->where('status', 'completed')->count();
        $collectedCount = $orders->where('status', 'collected')->count();
    @endphp

    <div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-4">
        <p class="text-xs text-gray-400 uppercase mb-1">Pending</p>
        <p class="text-2xl font-bold text-yellow-600">{{ $pendingCount }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4">
        <p class="text-xs text-gray-400 uppercase mb-1">Printing</p>
        <p class="text-2xl font-bold text-blue-600">{{ $printingCount }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4">
        <p class="text-xs text-gray-400 uppercase mb-1">Completed</p>
        <p class="text-2xl font-bold text-purple-600">{{ $completedCount }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4">
        <p class="text-xs text-gray-400 uppercase mb-1">Collected</p>
        <p class="text-2xl font-bold text-green-600">{{ $collectedCount }}</p>
    </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="w-full min-w-[720px]">
            <thead>
                <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wide">
                    <th class="p-4">Order ID</th>
                    <th class="p-4">Customer</th>
                    <th class="p-4">Contact</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Submitted</th>
                    <th class="p-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 font-mono text-sm text-gray-700">{{ $order->order_id }}</td>
                    <td class="p-4 text-sm text-gray-700">{{ $order->customer_name }}</td>
                    <td class="p-4 text-sm text-gray-500">{{ $order->contact_info }}</td>
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$order->status] }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="p-4 text-sm text-gray-500">{{ $order->created_at->format('d M, h:i A') }}</td>
                    <td class="p-4 text-right">
                        <a href="{{ route('staff.show', $order) }}"
                           class="text-yellow-700 text-sm font-medium hover:underline">
                            View →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-400">No orders yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
</x-app-layout>