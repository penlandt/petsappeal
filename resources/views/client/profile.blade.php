<x-client-layout>
    <x-slot name="title">My Profile</x-slot>

    <div class="max-w-xl mx-auto mt-10 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Update Your Profile</h2>

        @if (session('success'))
            <div class="mb-4 text-green-600 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 text-red-500">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('client.profile.update') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-700 dark:text-gray-200">Email Address</label>
                <input type="email" name="email" id="email" required
                       value="{{ old('email', auth('client')->user()->email) }}"
                       class="w-full px-3 py-2 border rounded dark:bg-gray-900 dark:text-white"
                       style="background-color: #fff; color: #000;">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 dark:text-gray-200">New Password</label>
                <input type="password" name="password" id="password"
                       class="w-full px-3 py-2 border rounded dark:bg-gray-900 dark:text-white"
                       style="background-color: #fff; color: #000;">
                <small class="text-gray-500">Leave blank to keep current password.</small>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 dark:text-gray-200">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="w-full px-3 py-2 border rounded dark:bg-gray-900 dark:text-white"
                       style="background-color: #fff; color: #000;">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                Save Changes
            </button>
        </form>
    </div>
</x-client-layout>
