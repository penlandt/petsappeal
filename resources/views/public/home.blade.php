<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Welcome to PETSAppeal
        </h2>
    </x-slot>

    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white">
                    Pet Grooming & Retail Management Made Easy
                </h1>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    PETSAppeal is the all-in-one solution for pet grooming salons, boarding kennels, and pet supply stores.
                    Manage appointments, track clients, and grow your business with modern, user-friendly software.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('public.about') }}" class="px-5 py-2 rounded-lg bg-blue-600 text-white text-center hover:bg-blue-700">
                        Learn More
                    </a>
                    <a href="{{ route('public.pricing') }}" class="px-5 py-2 rounded-lg bg-green-600 text-white text-center hover:bg-green-700">
                        View Pricing
                    </a>
                    <a href="{{ route('public.contact') }}" class="px-5 py-2 rounded-lg bg-gray-600 text-white text-center hover:bg-gray-700">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
