<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Register
        </h2>
    </x-slot>

    <div class="py-12 flex justify-center">
        <div class="w-full max-w-md bg-white dark:bg-gray-800 p-6 shadow-md rounded" style="max-width: 448px;">
            <form method="POST" action="{{ route('register') }}" id="register-form">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input id="name" name="name" type="text" required autofocus
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input id="email" name="email" type="email" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                </div>

                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="terms" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            I agree to the
                            <a href="{{ route('terms') }}" class="underline text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200" target="_blank">Terms of Service</a>
                            and
                            <a href="{{ route('privacy') }}" class="underline text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200" target="_blank">Privacy Policy</a>.
                        </span>
                    </label>
                    @error('terms')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- reCAPTCHA token input -->
                <input type="hidden" name="recaptcha_token" id="recaptcha_token">

                <div class="flex items-center justify-between">
                    <a href="{{ route('login') }}" class="text-sm text-blue-500 hover:underline">Already registered?</a>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Register
                    </button>
                </div>
            </form>

            <p class="mt-6 text-sm text-gray-600 dark:text-gray-400 text-center">
                This site is protected by reCAPTCHA and the Google
                <a href="https://policies.google.com/privacy" target="_blank" class="underline hover:text-blue-500">Privacy Policy</a> and
                <a href="https://policies.google.com/terms" target="_blank" class="underline hover:text-blue-500">Terms of Service</a> apply.
            </p>
        </div>
    </div>

    <!-- reCAPTCHA v3 Script -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    <script>
        document.getElementById('register-form').addEventListener('submit', function (e) {
            e.preventDefault();

            grecaptcha.ready(function () {
                grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', { action: 'register' }).then(function (token) {
                    document.getElementById('recaptcha_token').value = token;
                    e.target.submit();
                });
            });
        });
    </script>
</x-guest-layout>
