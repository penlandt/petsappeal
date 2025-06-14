<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Login
        </h2>
    </x-slot>

    <div class="py-12 flex justify-center">
        <div class="max-w-md w-full bg-white dark:bg-gray-800 p-6 shadow-md rounded" style="max-width: 448px;">
            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input id="email" name="email" type="email" required autofocus
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    <input id="password" name="password" type="password" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">

                    @if (Route::has('password.request'))
                        <div class="mt-2">
                            <a href="{{ route('password.request') }}"
                            class="text-sm text-blue-500 hover:underline">
                                Forgot your password?
                            </a>
                        </div>
                    @endif
                </div>

                <!-- reCAPTCHA token -->
                <input type="hidden" name="recaptcha_token" id="recaptcha_token">

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Login
                    </button>
                    <a href="{{ route('register') }}" class="text-sm text-blue-500 hover:underline">Register</a>
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
    document.getElementById('login-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = this; // <== this is the correct reference to the form
        grecaptcha.ready(function () {
            grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', { action: 'login' }).then(function (token) {
                document.getElementById('recaptcha_token').value = token;
                form.submit(); // <== call submit on the actual form
            });
        });
    });
</script>

</x-guest-layout>
