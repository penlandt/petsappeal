<x-guest-layout>
    <x-slot name="title">Pricing</x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl font-extrabold text-center text-gray-900 dark:text-white mb-4">
                Simple, Modular Pricing
            </h1>

            <p class="text-center text-lg text-blue-600 dark:text-blue-400 font-semibold mb-12">
                Enjoy all features free for 15 days – no credit card required!
            </p>

            <!-- Modules -->
            <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-white mb-6">
                Choose Your Modules – $49/mo Each
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                @php
                    $modules = [
                        ['name' => 'Grooming', 'features' => ['Pet Grooming Scheduler', 'Client & Pet Management', 'Service Tracking & Notes']],
                        ['name' => 'Boarding', 'features' => ['Boarding Reservations', 'Multi-Pet Units & Charges', 'Custom Check-In/Out Times']],
                        ['name' => 'Daycare', 'features' => ['Full-Day & Half-Day Check-Ins', 'Attendance Tracking', 'Capacity & Staffing Management']],
                        ['name' => 'Pet & House Sitting', 'features' => ['In-Home Visit Scheduling', 'Client Instructions & Reminders', 'Visit Logs & Notes']],
                    ];
                @endphp

                @foreach ($modules as $module)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 flex flex-col justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">{{ $module['name'] }}</h2>
                            <p class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">$49/mo</p>
                            <ul class="text-gray-700 dark:text-gray-300 space-y-2 mb-6">
                                @foreach ($module['features'] as $feature)
                                    <li>{{ $feature }}</li>
                                @endforeach
                                <li><span class="font-semibold">POS Module Included</span></li>
                            </ul>
                        </div>
                        <div>
                            <a href="{{ route('register') }}"
                               class="block w-full text-center bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 transition">
                                Start Free Trial
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- POS Module Full Width -->
            <div class="mb-16">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 md:p-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="flex-1">
                        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">Point of Sale (POS) Module</h2>
                        <ul class="text-gray-700 dark:text-gray-300 space-y-2 mb-4 text-lg">
                            <li>Integrated Product Sales</li>
                            <li>Credit/Debit & Cash Payments</li>
                            <li>Returns, Discounts, & Inventory Tracking</li>
                            <li><span class="font-semibold">Included FREE with any module</span></li>
                        </ul>
                    </div>
                    <div class="w-full md:w-1/3">
                        <div class="text-center bg-blue-600 text-white font-semibold py-3 px-6 rounded hover:bg-blue-700 transition">
                            Always Free with Any Module
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Add-ons -->
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">Support Add-ons</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Priority Email/Chat Support</h3>
                        <p class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">$49/mo</p>
                        <ul class="text-gray-700 dark:text-gray-300 space-y-2 mb-6">
                            <li>Faster response time</li>
                            <li>Email and Live Chat access</li>
                            <li>Applies to all active modules</li>
                        </ul>
                    </div>
                    <div>
                        <a href="{{ route('register') }}"
                           class="block w-full text-center bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 transition">
                            Add During Signup
                        </a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Real-Time Phone/Screen-Sharing Support</h3>
                        <p class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">$99/mo</p>
                        <ul class="text-gray-700 dark:text-gray-300 space-y-2 mb-6">
                            <li>Live Zoom/Meet assistance</li>
                            <li>Ideal for setup or training</li>
                            <li>Applies to all active modules</li>
                        </ul>
                    </div>
                    <div>
                        <a href="{{ route('register') }}"
                           class="block w-full text-center bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 transition">
                            Add During Signup
                        </a>
                    </div>
                </div>
            </div>

            <!-- Additional Services -->
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
                    </ul>
                </div>

                <!-- Hourly Services -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-xl font-bold text-blue-600 dark:text-blue-400 mb-4">Hourly Services</h3>
                    <ul class="space-y-4 text-gray-700 dark:text-gray-300">
                        <li>
                            <strong>Custom Report Design:</strong><br>
                            On-screen or PDF reports tailored to your needs – <span class="font-semibold">$180/hr</span>
                        </li>
                        <li>
                            <strong>Custom Software Development:</strong><br>
                            Add features unique to your business – <span class="font-semibold">$225/hr</span>
                        </li>
                        <li>
                            <strong>Data Export Assistance:</strong><br>
                            Help extracting data from legacy systems – <span class="font-semibold">$135/hr</span>
                        </li>
                        <li>
                            <strong>Screen-Sharing Support:</strong><br>
                            Live Zoom/Meet help outside a support plan – <span class="font-semibold">$112.50/hr</span>
                        </li>
                        <li>
                            <strong>Printed Materials Customization:</strong><br>
                            Branded intake forms, receipts, invoices – <span class="font-semibold">$90/hr</span>
                        </li>
                    </ul>
                </div>
            </div>

            <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                Have questions? <a href="{{ route('public.contact') }}" class="text-blue-600 dark:text-blue-400 underline">Contact us</a> and we’ll help you build your perfect plan.
            </p>
        </div>
    </div>
</x-guest-layout>
