<x-app-layout>
<div class="max-w-md mx-auto mt-8 px-4">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Add Staff Account</h2>
        <p class="text-gray-500 text-sm">Create a login for a staff member or supervisor</p>
    </div>

    <form method="POST" action="{{ route('staff.accounts.store') }}" class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition">
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select name="role" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition">
                <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="supervisor" {{ old('role') === 'supervisor' ? 'selected' : '' }}>Supervisor</option>
            </select>
            <p class="text-gray-400 text-xs mt-1">Supervisors can view reports and manage staff accounts. Staff can manage orders only.</p>
            @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition">
            <p class="text-gray-400 text-xs mt-1">At least 10 characters with upper and lower case, a number and a symbol. The user must change it at first login.</p>
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition">
        </div>

        <div class="flex gap-2 pt-2">
            <button type="submit" class="bg-yellow-400 text-gray-900 font-medium px-5 py-2.5 rounded-lg hover:bg-yellow-500 transition">
                Create Account
            </button>
            <a href="{{ route('staff.accounts.index') }}" class="bg-gray-100 text-gray-600 font-medium px-5 py-2.5 rounded-lg hover:bg-gray-200 transition">
                Cancel
            </a>
        </div>
    </form>

</div>
</x-app-layout>
