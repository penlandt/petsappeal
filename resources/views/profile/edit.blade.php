<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">Edit Profile</h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-xl mx-auto">
        @if (session('success'))
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="bg-white dark:bg-gray-800 shadow-sm rounded p-6">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
                <input
                    type="password"
                    name="password"
                    required
                    style="background-color: #fff; color: #000;"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500"
                />
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    required
                    style="background-color: #fff; color: #000;"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500"
                />
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
