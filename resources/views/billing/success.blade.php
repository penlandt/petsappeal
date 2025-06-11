<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Subscription Successful') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-2xl font-bold text-green-600 dark:text-green-400 mb-4">
                Thank you for upgrading!
            </h3>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
                Your subscription has been activated. You now have access to all the features of your selected plan.
            </p>
            <a href="{{ route('dashboard') }}" class="inline-block px-4 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700">
                Go to Dashboard
            </a>
        </div>
    </div>
</x-app-layout>
