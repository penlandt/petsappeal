<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

    <div class="min-h-screen">
        @include('layouts.navigation')

        <main class="flex justify-center pt-6 sm:pt-12 px-4">
            <div class="max-w-md w-full bg-white dark:bg-gray-800 p-6 shadow-md rounded text-black dark:text-white">

                <h1 class="text-2xl font-bold mb-4">Forgot Your Password?</h1>

                <p class="mb-4">Enter your email address to receive a password reset link.</p>

                @if (session('status'))
                    <div class="text-green-500 mb-4">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="text-red-500 mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium">Email</label>
                        <input type="email" name="email" id="email"
                               class="mt-1 block w-full border border-gray-300 rounded p-2 dark:bg-gray-700 dark:text-white"
                               required autofocus>
                    </div>
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Email Password Reset Link
                    </button>
                </form>

            </div>
        </main>
    </div>

</body>
</html>
