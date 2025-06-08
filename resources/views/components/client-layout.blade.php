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
        <header class="bg-white dark:bg-gray-800 shadow p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Client Portal
                </h1>
                <nav class="space-x-4">
                    <a href="{{ route('client.dashboard') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Dashboard</a>
                    <a href="{{ route('client.pets.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Pets</a>
                    <form method="POST" action="{{ route('client.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:underline">Log Out</button>
                    </form>
                </nav>
            </div>
        </header>

        <main class="py-10 px-4">
            @hasSection('content')
                @yield('content')
            @elseif (isset($slot))
                {{ $slot }}
            @else
                {{-- Safely fallback for non-component pages without $slot or @section --}}
            @endif
        </main>


    </div>
</body>
</html>
