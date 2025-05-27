<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Login
        </h2>
    </x-slot>

    <div class="py-12 flex justify-center">
        <div class="max-w-md w-full bg-white dark:bg-gray-800 p-6 shadow-md rounded" style="max-width: 448px;">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input id="email" name="email" type="email" required autofocus
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    <input id="password" name="password" type="password" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Login
                    </button>
                    <a href="{{ route('register') }}" class="text-sm text-blue-500 hover:underline">Register</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
