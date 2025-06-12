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

    <!-- Hero Section -->
    <div class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">
                    Powerful Software for Groomers, Kennels & Pet Care Pros
                </h1>
                <p class="text-lg text-gray-700 dark:text-gray-300 mb-6">
                    PETSAppeal helps you manage appointments, clients, pets, POS, boarding, and staff — all in one beautiful and easy-to-use system.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg">
                        Start Free Trial
                    </a>
                    <a href="{{ route('public.about') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                        Learn More
                    </a>
                    <a href="{{ route('public.pricing') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg">
                        View Pricing
                    </a>
                </div>
            </div>
            <div>
                <img src="{{ asset('images/groomer-fluffy-dog-hero.png') }}" alt="Groomer brushing fluffy white dog"
                    class="w-full rounded-lg shadow-lg">
            </div>
        </div>
    </div>

    <!-- Feature Highlights -->
    <div class="bg-gray-50 dark:bg-gray-800 py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Accept Credit Cards</h3>
                    <p class="text-gray-700 dark:text-gray-300">Built-in Stripe integration lets you take secure payments at checkout.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Online Booking Portal</h3>
                    <p class="text-gray-700 dark:text-gray-300">Your clients can request appointments and view their pet’s history online.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">All-in-One Platform</h3>
                    <p class="text-gray-700 dark:text-gray-300">Manage grooming, boarding, retail, invoicing, staff, and inventory.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Final CTA -->
    <div class="bg-white dark:bg-gray-900 py-20 text-center">
        <div class="max-w-3xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Ready to simplify your pet care business?</h2>
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-8">
                Start your free trial today and see why groomers love PETSAppeal.
            </p>
            <a href="{{ route('register') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg">
                Get Started Free
            </a>
        </div>
    </div>
</x-app-layout>
