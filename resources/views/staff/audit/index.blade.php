<x-app-layout>
<div class="max-w-6xl mx-auto mt-8 px-4">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Audit Log</h2>
        <p class="text-gray-500 text-sm">Who viewed, printed or changed what, and when</p>
    </div>

    <form method="GET" action="{{ route('staff.audit') }}" class="mb-6">
        <div class="flex gap-2">
            <input type="text" name="search" value="{{ $search ?? '' }}"
                placeholder="Search by order ID, user or action..."
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition">
            <button type="submit" class="bg-yellow-400 text-gray-900 font-medium px-5 rounded-lg hover:bg-yellow-500 transition">
                Search
            </button>
            @if(!empty($search))
                <a href="{{ route('staff.audit') }}" class="bg-gray-100 text-gray-600 font-medium px-4 py-2.5 rounded-lg hover:bg-gray-200 transition">Clear</a>
            @endif
        </div>
    </form>

    <div class="bg-white rounded-xl shadow-sm overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-5 py-3">Time</th>
                    <th class="px-5 py-3">User</th>
                    <th class="px-5 py-3">Action</th>
                    <th class="px-5 py-3">Order</th>
                    <th class="px-5 py-3">Detail</th>
                    <th class="px-5 py-3">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                    <tr>
                        <td class="px-5 py-3 text-gray-500 whitespace-nowrap">{{ $log->created_at->format('d M Y, h:i:s A') }}</td>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $log->user_name ?? '—' }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $log->action === 'login_failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ str_replace('_', ' ', $log->action) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 font-mono text-gray-700">{{ $log->order_id ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $log->detail ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-400">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">No activity recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $logs->links() }}</div>

</div>
</x-app-layout>
