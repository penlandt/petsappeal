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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <!-- Pricing Plans -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-2 text-gray-800 dark:text-white">Starter</h2>
                    <p class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">$49/mo</p>
                    <ul class="text-gray-700 dark:text-gray-300 space-y-2">
                        <li>1 Location</li>
                        <li>Basic Support</li>
                        <li>All Core Features</li>
                    </ul>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-2 text-gray-800 dark:text-white">Pro</h2>
                    <p class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">$99/mo</p>
                    <ul class="text-gray-700 dark:text-gray-300 space-y-2">
                        <li>Up to 3 Locations</li>
                        <li>Priority Email Support</li>
                        <li>All Core Features</li>
                    </ul>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-2 text-gray-800 dark:text-white">Multi-Location</h2>
                    <p class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">$149/mo</p>
                    <ul class="text-gray-700 dark:text-gray-300 space-y-2">
                        <li>Unlimited Locations</li>
                        <li>Priority Phone Support</li>
                        <li>All Core Features</li>
                    </ul>
                </div>
            </div>

            <!-- Ancillary Services Section -->
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6 text-center">Additional Services</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <!-- Flat-Fee Services -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-xl font-bold text-blue-600 dark:text-blue-400 mb-4">Flat-Fee Services</h3>
                    <ul class="space-y-4 text-gray-700 dark:text-gray-300">
                        <li>
                            <strong>Client Onboarding & Training:</strong><br>
                            Personalized setup and walkthrough – <span class="font-semibold">$270 one-time</span>
                        </li>
                        <li>
                            <strong>Basic Data Import (≤500 records):</strong><br>
                            Import pets, clients, and services into PETSAppeal – <span class="font-semibold">$395 one-time</span>
                        </li>
                        <li>
                            <strong>Priority Support Plan:</strong><br>
                            Faster responses for urgent issues – <span class="font-semibold">$89/month</span>
                        </li>
                    </ul>
                </div>

                <!-- Hourly Services -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-xl font-bold text-blue-600 dark:text-blue-400 mb-4">Hourly Services</h3>
                    <ul class="space-y-4 text-gray-700 dark:text-gray-300">
                        <li>
                            <strong>Data Export Assistance:</strong><br>
                            Help extracting data from legacy systems – <span class="font-semibold">$135/hr</span>
                        </li>
                        <li>
                            <strong>Custom Report Design:</strong><br>
                            On-screen or PDF reports tailored to your needs – <span class="font-semibold">$180/hr</span>
                        </li>
                        <li>
                            <strong>Custom Software Development:</strong><br>
                            Add features unique to your business – <span class="font-semibold">$225/hr</span>
                        </li>
                        <li>
                            <strong>Screen-Sharing Support:</strong><br>
                            Live Zoom/Meet assistance with setup or training – <span class="font-semibold">$112.50/hr</span>
                        </li>
                        <li>
                            <strong>Printed Materials Customization:</strong><br>
                            Branded intake forms, receipts, and invoices – <span class="font-semibold">$90/hr</span>
                        </li>
                    </ul>
                </div>
            </div>

            <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                Need something not listed here? <a href="{{ route('public.contact') }}" class="text-blue-600 dark:text-blue-400 underline">Contact us</a> for a custom quote.
            </p>
        </div>
    </div>
</x-guest-layout>
