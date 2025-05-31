<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Access Denied
        </h2>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-red-600 dark:text-red-400 mb-6">🚫 Access Denied</h1>
        <p class="text-lg text-gray-700 dark:text-gray-300 mb-4">
            You don’t have access to this module yet.
        </p>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
            Upgrade your subscription to unlock this feature.
        </p>
        <a href="{{ route('public.pricing') }}"
           class="inline-block px-5 py-3 bg-blue-600 text-white rounded hover:bg-blue-700">
            View Plans & Pricing
        </a>
    </div>
</x-app-layout>
