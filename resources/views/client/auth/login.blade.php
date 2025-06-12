<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 bg-white dark:bg-gray-800 p-8 rounded shadow">
        <div class="text-center mb-6">
            <img src="{{ asset('storage/company-assets/company_' . $company->id . '_logo.png') }}"
                alt="{{ $company->name }} Logo"
                class="h-20 mx-auto mb-6">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                {{ $company->name }} Client Login
            </h1>
        </div>

        <form method="POST" action="{{ route('client.login.submit', ['companySlug' => $company->slug]) }}" id="client-login-form">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input id="email" name="email" type="email" required autofocus
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <input id="password" name="password" type="password" required
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
            </div>

            <!-- reCAPTCHA token -->
            <input type="hidden" name="recaptcha_token" id="recaptcha_token">

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                Log In
            </button>
        </form>

        <p class="mt-6 text-sm text-gray-600 dark:text-gray-400 text-center">
            This site is protected by reCAPTCHA and the Google
            <a href="https://policies.google.com/privacy" target="_blank" class="underline hover:text-blue-500">Privacy Policy</a> and
            <a href="https://policies.google.com/terms" target="_blank" class="underline hover:text-blue-500">Terms of Service</a> apply.
        </p>
    </div>

    <!-- reCAPTCHA v3 Script -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    <script>
        document.getElementById('client-login-form').addEventListener('submit', function (e) {
            e.preventDefault();
            grecaptcha.ready(function () {
                grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', { action: 'client_login' }).then(function (token) {
                    document.getElementById('recaptcha_token').value = token;
                    e.target.submit();
                });
            });
        });
    </script>
</x-guest-layout>
