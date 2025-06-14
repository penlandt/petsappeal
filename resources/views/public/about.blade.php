<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            About PETSAppeal
        </h2>
    </x-slot>

    <!-- Top Auto-Scrolling Image Carousel -->
    <div class="overflow-hidden bg-white dark:bg-gray-900 py-4">
        <div class="relative w-full">
            <div class="flex w-max animate-scroll whitespace-nowrap px-0">
                @foreach ([1, 2] as $loopPass)
                    @foreach ([
                        'grooming-salon-interior.png',
                        'doggy-daycare-interior.png',
                        'mobile-pet-groomer.png',
                        'pet-sitter-with-cats.png',
                        'boarding-kennel-interior.png'
                    ] as $image)
                        <img src="{{ asset('images/' . $image) }}"
                             alt="{{ $image }}"
                             style="height: 170px; width: 225px; object-fit: cover;"
                             class="inline-block rounded shadow" />
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>

    <!-- About Text Content -->
    <div class="py-12 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-gray-800 dark:text-gray-200 space-y-6">
            <p>
                PETSAppeal is a modern, web-based software platform purpose-built for pet grooming salons, mobile groomers, and pet care businesses. Whether you're a solo operator or managing a multi-location enterprise, PETSAppeal streamlines your operations and scales as you grow.
            </p>
            <p>
                Created by people who understand both pet care and technology, PETSAppeal is easy to use but packed with powerful features — giving you more time to focus on your clients (both two- and four-legged).
            </p>

            <p class="text-lg font-semibold">🔧 What You Can Do with PETSAppeal</p>
            <ul class="list-disc list-inside space-y-1">
                <li>Manage grooming, boarding, and retail POS operations in one system</li>
                <li>Schedule appointments with an interactive calendar</li>
                <li>Track pet history, grooming notes, and client communications</li>
                <li>Process payments and generate polished, detailed invoices</li>
                <li>Monitor staff schedules, availability, and time off</li>
                <li>Track inventory for both retail and services</li>
            </ul>

            <p class="text-lg font-semibold">✨ What’s New</p>
            <ul class="list-disc list-inside space-y-1">
                <li>✅ Accept Credit Cards Directly in POS<br>
                    Your business can now accept secure card payments — including live charges through Stripe Connect — at the point of sale, right inside PETSAppeal.
                </li>
                <li>✅ Let Your Clients Book Online<br>
                    Your clients can now log into their own portal to request appointments online, check their pet records, and more — all while you stay in control.
                </li>
            </ul>

            <p class="text-lg font-semibold">✅ What’s Available Now</p>
            <ul class="list-disc list-inside space-y-1">
                <li>Grooming – Full-featured scheduling with pet and client management</li>
                <li>Boarding – Overnight stay management with multi-pet reservations</li>
                <li>Point of Sale (POS) – Unified retail and service billing with Stripe integration</li>
                <li>Inventory – Track retail and service items across locations</li>
                <li>Invoicing – Generate, customize, and track invoices system-wide</li>
            </ul>

            <p class="text-lg font-semibold">🚀 Coming Soon</p>
            <ul class="list-disc list-inside space-y-1">
                <li>Daycare – Hourly and daily check-ins for short-term pet care</li>
                <li>Pet & House Sitting – Manage off-site visits, assignments, and availability</li>
                <li>Reporting – Generate real-time reports for performance, trends, and forecasts</li>
            </ul>

            <p>
                PETSAppeal isn’t just software — it’s a partner in your success.
                Whether you’re just starting out or ready to expand, we’re building the tools to help you thrive in the pet care business.
            </p>
        </div>
    </div>

    <!-- Bottom Auto-Scrolling Image Carousel -->
    <div class="overflow-hidden bg-white dark:bg-gray-900 py-4">
        <div class="relative w-full">
            <div class="flex w-max animate-scroll whitespace-nowrap px-0">
                @foreach ([1, 2] as $loopPass)
                    @foreach ([
                        'grooming-salon-interior.png',
                        'doggy-daycare-interior.png',
                        'mobile-pet-groomer.png',
                        'pet-sitter-with-cats.png',
                        'boarding-kennel-interior.png'
                    ] as $image)
                        <img src="{{ asset('images/' . $image) }}"
                             alt="{{ $image }}"
                             style="height: 170px; width: 225px; object-fit: cover;"
                             class="inline-block rounded shadow" />
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>

    <!-- Carousel Animation -->
    <style>
        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .animate-scroll {
            animation: scroll 50s linear infinite;
        }
    </style>
</x-guest-layout>
