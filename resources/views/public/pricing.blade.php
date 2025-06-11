<x-guest-layout>
    <x-slot name="title">Pricing</x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl font-extrabold text-center text-gray-900 dark:text-white mb-4">
                Simple, Transparent Pricing
            </h1>

            <p class="text-center text-lg text-blue-600 dark:text-blue-400 font-semibold mb-12">
                Start with a <strong>15-day free trial</strong> – all features, no credit card required!
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Starter Plan -->
                <div class="border border-gray-300 dark:border-gray-700 rounded-lg p-6 shadow-sm bg-white dark:bg-gray-800">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">PETSAppeal Starter</h2>
                    <p class="mt-4 text-3xl font-extrabold text-gray-900 dark:text-white">$49<span class="text-base font-medium text-gray-600 dark:text-gray-300">/mo</span></p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">or $499/year (15% off)</p>

                    <ul class="mt-4 space-y-2 text-sm text-gray-700 dark:text-gray-200">
                        <li>✓ Appointment Scheduling</li>
                        <li>✓ Client & Pet Management</li>
                        <li>✓ Single Location</li>
                        <li>✓ Free 24hr Turnaround Email Support</li>
                    </ul>

                    <div class="mt-6">
                        <a href="{{ route('register') }}"
                           class="w-full block text-center py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded">
                            Get Started
                        </a>
                    </div>
                </div>

                <!-- Pro Plan -->
                <div class="border-2 border-blue-600 rounded-lg p-6 shadow-md bg-white dark:bg-gray-800">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">PETSAppeal Pro</h2>
                    <p class="mt-4 text-3xl font-extrabold text-gray-900 dark:text-white">$99<span class="text-base font-medium text-gray-600 dark:text-gray-300">/mo</span></p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">or $999/year (16% off)</p>

                    <ul class="mt-4 space-y-2 text-sm text-gray-700 dark:text-gray-200">
                        <li>✓ Everything in Starter</li>
                        <li>✓ Staff Scheduling</li>
                        <li>✓ Boarding & Daycare</li>
                        <li>✓ Priority Email Support</li>
                    </ul>

                    <div class="mt-6">
                        <a href="{{ route('register') }}"
                           class="w-full block text-center py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded">
                            Try Pro
                        </a>
                    </div>
                </div>

                <!-- Multi-Location Plan -->
                <div class="border border-gray-300 dark:border-gray-700 rounded-lg p-6 shadow-sm bg-white dark:bg-gray-800">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">PETSAppeal Multi-Location</h2>
                    <p class="mt-4 text-3xl font-extrabold text-gray-900 dark:text-white">$149<span class="text-base font-medium text-gray-600 dark:text-gray-300">/mo</span></p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">or $1,499/year (16% off)</p>

                    <ul class="mt-4 space-y-2 text-sm text-gray-700 dark:text-gray-200">
                        <li>✓ Everything in Pro</li>
                        <li>✓ Unlimited Locations</li>
                        <li>✓ Advanced Reporting</li>
                        <li>✓ Priority Phone Support</li>
                    </ul>

                    <div class="mt-6">
                        <a href="{{ route('register') }}"
                           class="w-full block text-center py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded">
                            Upgrade Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
