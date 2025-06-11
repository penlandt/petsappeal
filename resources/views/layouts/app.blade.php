<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PETSAppeal') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        @keyframes spinY {
            from { transform: rotateY(0deg); }
            to { transform: rotateY(360deg); }
        }

        #loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            display: none;
        }

        #loading-overlay img {
            width: 100px;
            height: 100px;
            animation: spinY 2.5s linear infinite;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100" data-session-lifetime="{{ config('session.lifetime') }}">

    <!-- Spinner Overlay -->
    <div id="loading-overlay">
        <img src="{{ asset('images/petsappeal-logo-square.png') }}" alt="Loading..." />
    </div>

    <div class="min-h-screen">
        @include('layouts.navigation')

        {{-- 🔔 Trial Expired Banner --}}
        @php
            $company = Auth::user()?->company;
            $isTrialExpired = $company?->trial_ends_at && now()->greaterThan($company->trial_ends_at);
            $isSubscribed = $company?->subscribed('default');
        @endphp

        @if ($isTrialExpired && !$isSubscribed && !Request::is('billing/*'))
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 text-center">
                <strong>Your free trial has ended.</strong>
                Please <a href="{{ route('billing.plans') }}" class="underline font-semibold">select a subscription plan</a> to continue using PETSAppeal.
            </div>
        @endif

        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="py-12">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    @stack('scripts')

    <script>
        window.addEventListener('beforeunload', function () {
            document.getElementById('loading-overlay').style.display = 'flex';
        });

        window.addEventListener('pageshow', function () {
            document.getElementById('loading-overlay').style.display = 'none';
        });

        document.addEventListener('DOMContentLoaded', function () {
            const links = document.querySelectorAll('a[href]');
            links.forEach(link => {
                link.addEventListener('click', function (e) {
                    const url = new URL(link.href, window.location.href);
                    const isSameOrigin = url.origin === window.location.origin;
                    const isInternal = isSameOrigin && !url.href.includes('#') && !url.href.startsWith('javascript:');

                    if (isInternal && !link.hasAttribute('target')) {
                        document.getElementById('loading-overlay').style.display = 'flex';
                    }
                });
            });
        });
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
