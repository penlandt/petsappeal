<x-guest-layout>
    <x-slot name="title">Change Your Password</x-slot>

    <div class="max-w-md mx-auto mt-10 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Change Your Password</h2>

        @if ($errors->any())
            <div class="mb-4 text-red-500">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('client.password.update') }}">
            @csrf

            <div class="mb-4">
                <label for="password" class="block text-gray-700 dark:text-gray-200">New Password</label>
                <input type="password" name="password" id="password" required
                       class="w-full px-3 py-2 border rounded dark:bg-gray-900 dark:text-white"
                       style="background-color: #fff; color: #000;">
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 dark:text-gray-200">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="w-full px-3 py-2 border rounded dark:bg-gray-900 dark:text-white"
                       style="background-color: #fff; color: #000;">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                Update Password
            </button>
        </form>
    </div>
</x-guest-layout>
