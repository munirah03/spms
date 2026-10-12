<x-app-layout>
    @php $isSupervisor = $user->role === 'supervisor'; @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ $isSupervisor ? 'Supervisor Profile' : 'Staff Profile' }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('must_change') || $user->must_change_password)
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    Please set a new password before using the system. Your current password was set by a supervisor.
                </div>
            @endif

            <div class="p-6 bg-white shadow sm:rounded-lg flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-yellow-400 text-gray-900 flex items-center justify-center text-2xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-lg font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full bg-gray-900 text-yellow-300 text-xs font-medium uppercase tracking-wide">
                        {{ $user->role }}
                    </span>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <p class="text-xs text-gray-600 text-center">
                {{ $isSupervisor
                    ? 'Staff accounts are created and removed under Manage Users.'
                    : 'To remove or change your access, please contact your supervisor.' }}
            </p>
        </div>
    </div>
</x-app-layout>
