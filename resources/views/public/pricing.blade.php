@extends('layouts.guest')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Pricing</h1>

    <p class="mb-6 text-lg">
        PETSAppeal offers flexible pricing plans to grow with your grooming business. Whether you're just getting started or managing a full salon staff, we have a plan that fits.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <h2 class="text-xl font-bold mb-2">Free Plan</h2>
            <p class="mb-4 text-gray-600 dark:text-gray-300">Up to 10 clients</p>
            <p class="text-2xl font-semibold mb-2">$0/month</p>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow border border-blue-500">
            <h2 class="text-xl font-bold mb-2">Standard Plan</h2>
            <p class="mb-4 text-gray-600 dark:text-gray-300">Up to 25 clients</p>
            <p class="text-2xl font-semibold mb-2">$24.99/month</p>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <h2 class="text-xl font-bold mb-2">Pro Plan</h2>
            <p class="mb-4 text-gray-600 dark:text-gray-300">Up to 100 clients</p>
            <p class="text-2xl font-semibold mb-2">$99.99/month</p>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow md:col-span-3">
            <h2 class="text-xl font-bold mb-2">Unlimited Plan</h2>
            <p class="mb-4 text-gray-600 dark:text-gray-300">No client limits. All features included.</p>
            <p class="text-2xl font-semibold mb-2">$199.99/month</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 italic">Annual plans receive a 10% discount</p>
        </div>
    </div>

    <a href="{{ route('register') }}" class="inline-block px-6 py-3 bg-blue-600 text-white text-lg font-semibold rounded hover:bg-blue-700 transition">
        Start Free Today
    </a>
@endsection
