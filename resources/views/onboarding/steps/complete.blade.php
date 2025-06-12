<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            🎉 Onboarding Complete
        </h2>
    </x-slot>

    <div class="py-16 px-6 max-w-2xl mx-auto text-center">
        <div class="bg-white dark:bg-gray-800 p-8 rounded shadow">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">You're all set!</h3>
            <p class="text-gray-700 dark:text-gray-300 mb-6">
                You've completed the setup for your company, location, staff, and services.
                You can now start using PETSAppeal's features.
            </p>
            <a href="{{ route('dashboard') }}"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded transition">
                Go to Dashboard
            </a>
        </div>
    </div>
</x-app-layout>
