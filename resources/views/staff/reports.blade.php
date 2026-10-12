<x-app-layout>
<div class="max-w-5xl mx-auto mt-8 px-4">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daily Reports</h2>
        <p class="text-gray-500 text-sm">Click a date to view the full order list for that day</p>
    </div>

    <form method="GET" action="{{ route('staff.reports') }}" class="mb-6">
        <div class="flex gap-2">
            <input type="date" name="search" value="{{ $search ?? '' }}"
                class="border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition">
            <button type="submit" class="bg-yellow-400 text-gray-900 font-medium px-5 rounded-lg hover:bg-yellow-500 transition">
                Search
            </button>
            @if(!empty($search))
                <a href="{{ route('staff.reports') }}" class="bg-gray-100 text-gray-600 font-medium px-4 py-2.5 rounded-lg hover:bg-gray-200 transition">
                    Clear
                </a>
            @endif
        </div>
    </form>

    @if(empty($search) && $todayReport->total_orders > 0)
        <div class="bg-yellow-50 border border-yellow-300 rounded-xl p-5 mb-6">
            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></span>
                    <h3 class="font-bold text-yellow-800">Today — {{ \Carbon\Carbon::parse($todayReport->report_date)->format('d M Y') }} (Live)</h3>
                </div>
                <span class="text-xs text-yellow-700">Updates automatically as orders are managed</span>
            </div>
            <div class="grid grid-cols-6 gap-3 text-sm">
                <div><p class="text-yellow-700 text-xs uppercase">Total</p><p class="font-bold text-yellow-800">{{ $todayReport->total_orders }}</p></div>
                <div><p class="text-yellow-600 text-xs uppercase">Pending</p><p class="font-bold text-yellow-700">{{ $todayReport->pending_count }}</p></div>
                <div><p class="text-yellow-700 text-xs uppercase">Printing</p><p class="font-bold text-yellow-800">{{ $todayReport->printing_count }}</p></div>
                <div><p class="text-purple-600 text-xs uppercase">Completed</p><p class="font-bold text-purple-700">{{ $todayReport->completed_count }}</p></div>
                <div><p class="text-green-600 text-xs uppercase">Collected</p><p class="font-bold text-green-700">{{ $todayReport->collected_count }}</p></div>
                <div><p class="text-yellow-700 text-xs uppercase">Total (RM)</p><p class="font-bold text-yellow-800">RM{{ number_format($todayReport->total_estimated_revenue, 2) }}</p></div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="w-full min-w-[640px]">
            <thead>
                <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wide">
                    <th class="p-4">Date</th>
                    <th class="p-4">Total Orders</th>
                    <th class="p-4">Pending</th>
                    <th class="p-4">Printing</th>
                    <th class="p-4">Completed</th>
                    <th class="p-4">Collected</th>
                    <th class="p-4">Total (RM)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reports as $r)
                <tr class="hover:bg-yellow-50 cursor-pointer transition"
                    onclick="window.location='{{ route('staff.report.detail', $r->report_date) }}'">
                    <td class="p-4 text-sm text-yellow-700 font-medium underline">{{ \Carbon\Carbon::parse($r->report_date)->format('d M Y') }}</td>
                    <td class="p-4 text-sm text-gray-700">{{ $r->total_orders }}</td>
                    <td class="p-4 text-sm text-yellow-600">{{ $r->pending_count }}</td>
                    <td class="p-4 text-sm text-yellow-700">{{ $r->printing_count }}</td>
                    <td class="p-4 text-sm text-purple-600">{{ $r->completed_count }}</td>
                    <td class="p-4 text-sm text-green-600">{{ $r->collected_count }}</td>
                    <td class="p-4 text-sm font-medium text-gray-800">RM{{ number_format($r->total_estimated_revenue, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-gray-400">
                        No archived reports yet. Reports are generated automatically at 9:00 PM daily.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
</x-app-layout>