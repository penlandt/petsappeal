@props(['header' => null])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Client Portal - PETSAppeal' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-sans antialiased">
    <div class="min-h-screen">
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Client Portal
                </h1>
                <nav class="space-x-4">
                    <a href="{{ route('client.dashboard') }}" class="text-gray-700 dark:text-gray-200 hover:underline">Dashboard</a>
                    <a href="{{ route('client.pets.index') }}" class="text-gray-700 dark:text-gray-200 hover:underline">Pets</a>
                    <a href="{{ route('client.appointments.request') }}" class="text-gray-700 dark:text-gray-200 hover:underline">Request Appointment</a>
                    <a href="{{ route('client.profile') }}" class="text-gray-700 dark:text-gray-200 hover:underline">My Profile</a>
                    <form method="POST" action="{{ route('client.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 dark:text-gray-200 hover:underline">Logout</button>
                    </form>
                </nav>
            </div>
        </header>

        @if ($header)
            <div class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </div>
        @endif

        <main class="py-10 px-4">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
