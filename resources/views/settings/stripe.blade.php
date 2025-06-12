<x-app-layout>
    <x-slot name="title">Stripe Settings</x-slot>

    <div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Stripe Settings</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4 dark:bg-green-800 dark:text-green-100">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4 dark:bg-red-800 dark:text-red-100">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
            <p class="text-gray-700 dark:text-gray-200 mb-4">
                This page allows you to connect your Stripe account to PETSAppeal for processing credit card payments through the Point of Sale system.
            </p>

            @if ($location && $location->stripe_account_id)
                <div class="text-green-700 dark:text-green-300 mb-4">
                    ✅ Stripe account connected (ID: <code>{{ $location->stripe_account_id }}</code>)
                </div>
            @else
                <div class="text-yellow-700 dark:text-yellow-300 mb-4">
                    ⚠️ No Stripe account connected yet.
                </div>
                <a href="{{ route('stripe.connect') }}"
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Connect Stripe Account
                </a>
            @endif
        </div>
    </div>
</x-app-layout>
