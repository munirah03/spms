<x-app-layout>
<div class="max-w-4xl mx-auto mt-8 px-4">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Staff Accounts</h2>
            <p class="text-gray-500 text-sm">Manage staff and supervisor logins</p>
        </div>
        <a href="{{ route('staff.accounts.create') }}"
            class="bg-yellow-400 text-gray-900 font-medium px-5 py-2.5 rounded-lg hover:bg-yellow-500 transition">
            + Add Account
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-5 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-5 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="w-full min-w-[640px] text-sm text-left">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-5 py-3">Name</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">Role</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($accounts as $account)
                    <tr>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $account->name }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $account->email }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $account->role === 'supervisor' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($account->role) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
                            <button type="button"
                                onclick="const m = document.getElementById('reset-modal-{{ $account->id }}'); m.classList.remove('hidden'); m.classList.add('flex');"
                                class="text-yellow-700 hover:text-yellow-800 text-xs font-medium mr-4">
                                Reset Password
                            </button>

                            @if($account->id !== auth()->id())
                                <form method="POST" action="{{ route('staff.accounts.destroy', $account) }}"
                                    class="inline"
                                    onsubmit="return confirm('Remove this account?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">
                                        Remove
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-300 text-xs">You</span>
                            @endif

                            <!-- Reset password modal -->
                            
                                <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-6 text-left">
                                    <h3 class="font-semibold text-gray-800 mb-1">Reset password</h3>
                                    <p class="text-xs text-gray-500 mb-4">
                                        Set a temporary password for <span class="font-medium">{{ $account->name }}</span>.
                                        They must change it at their next login.
                                    </p>
                                    <form method="POST" action="{{ route('staff.accounts.reset-password', $account) }}" class="space-y-3">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <input type="password" name="password" required minlength="10"
                                                placeholder="New temporary password"
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none">
                                        </div>
                                        <div>
                                            <input type="password" name="password_confirmation" required minlength="10"
                                                placeholder="Confirm new password"
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none">
                                        </div>
                                        <p class="text-[11px] text-gray-400">At least 10 characters, with upper &amp; lower case, a number and a symbol.</p>
                                        @error('password') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                        <div class="flex justify-end gap-2 pt-2">
                                            <button type="button"
                                                onclick="const m = document.getElementById('reset-modal-{{ $account->id }}'); m.classList.add('hidden'); m.classList.remove('flex');"
                                                class="px-4 py-2 text-xs font-medium text-gray-500 hover:bg-gray-100 rounded-lg">
                                                Cancel
                                            </button>
                                            <button type="submit"
                                                class="px-4 py-2 text-xs font-medium bg-yellow-400 text-gray-900 rounded-lg hover:bg-yellow-500">
                                                Reset Password
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
</x-app-layout>
