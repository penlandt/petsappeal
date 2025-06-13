<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'PETSAppeal' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-sans antialiased">

    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center" x-data="{ open: false }">
            <a href="{{ route('public.home') }}" class="text-xl font-bold text-gray-800 dark:text-white">
                PETSAppeal
            </a>

            <!-- Hamburger Button -->
            <button @click="open = !open" class="sm:hidden focus:outline-none">
                <svg class="w-6 h-6 text-gray-800 dark:text-gray-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Links (hidden on small screens) -->
            <div class="hidden sm:flex space-x-4 items-center">
                <a href="{{ route('public.home') }}" class="hover:underline text-gray-700 dark:text-gray-300">Home</a>
                <a href="{{ route('public.about') }}" class="hover:underline text-gray-700 dark:text-gray-300">About</a>
                <a href="{{ route('public.pricing') }}" class="hover:underline text-gray-700 dark:text-gray-300">Pricing</a>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="sm:hidden mt-4 px-2" x-show="open" x-transition>
            <a href="{{ route('public.home') }}" class="block py-2 text-gray-700 dark:text-gray-300">Home</a>
            <a href="{{ route('public.about') }}" class="block py-2 text-gray-700 dark:text-gray-300">About</a>
            <a href="{{ route('public.pricing') }}" class="block py-2 text-gray-700 dark:text-gray-300">Pricing</a>
            <a href="{{ route('public.contact') }}" class="block py-2 text-gray-700 dark:text-gray-300">Contact</a>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-10">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <footer class="mt-12 border-t border-gray-200 dark:border-gray-700 py-6 text-center text-sm text-gray-500 dark:text-gray-400 space-y-2">
        <div class="space-x-4">
            <a href="{{ route('terms') }}" class="hover:underline">
                Terms of Service
            </a>
            <a href="{{ route('privacy') }}" class="hover:underline">
                Privacy Policy
            </a>
            <a href="{{ route('public.contact') }}" class="hover:underline">Contact</a>
        </div>
        <div>
            &copy; {{ date('Y') }} PETSAppeal. All rights reserved.
        </div>
    </footer>

</body>
</html>
