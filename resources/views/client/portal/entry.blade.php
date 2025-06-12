<x-client-guest-layout>
    <div class="max-w-xl mx-auto text-center mt-10">
        <img src="{{ asset('storage/company-assets/company_' . $company->id . '_logo.png') }}"
             alt="{{ $company->name }} Logo"
             class="h-24 mx-auto mb-6">

        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
            Welcome to {{ $company->name }}'s Client Portal
        </h1>

        <p class="text-gray-600 dark:text-gray-300 mb-6">
            Please log in to book grooming appointments for your pets.
        </p>

        <div class="flex justify-center">
            <a href="{{ route('client.login', ['companySlug' => $company->slug]) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow">
                Log In
            </a>
        </div>
    </div>
</x-client-guest-layout>
