<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'PETSAppeal' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-sans antialiased">

    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
        <a href="{{ route('public.home') }}" class="text-xl font-bold text-gray-800 dark:text-white">
            PETSAppeal
        </a>

        <div class="space-x-4 flex items-center">
            <a href="{{ route('public.home') }}" class="hover:underline text-gray-700 dark:text-gray-300">Home</a>
            <a href="{{ route('public.about') }}" class="hover:underline text-gray-700 dark:text-gray-300">About</a>
            <a href="{{ route('public.pricing') }}" class="hover:underline text-gray-700 dark:text-gray-300">Pricing</a>
            <a href="{{ route('public.contact') }}" class="hover:underline text-gray-700 dark:text-gray-300">Contact</a>

            @guest
                <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Log in</a>
                <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Register</a>
            @endguest
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-10">
        @yield('content')
    </main>

</body>
</html>
