<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Choose a Plan') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($plans as $plan)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 flex flex-col justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $plan['name'] }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 text-lg my-2">${{ $plan['price'] }}/month</p>
                            <ul class="mb-4 text-sm text-gray-700 dark:text-gray-300">
                                @foreach ($plan['features'] as $feature)
                                    <li class="mb-1">• {{ $feature }}</li>
                                @endforeach
                            </ul>
                        </div>

                        @auth
                            <form method="POST" action="{{ route('billing.checkout') }}">
                                @csrf
                                <input type="hidden" name="price_id" value="{{ $plan['price_id'] }}">
                                <x-primary-button class="w-full">
                                    Upgrade to {{ $plan['name'] }}
                                </x-primary-button>
                            </form>
                        @else
                            <a href="{{ route('register') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-200 transition">
                                Start Free Trial
                            </a>
                        @endauth
                    </div>
                @endforeach
            </div>

            {{-- Cancel Button --}}
            @auth
                @if ($company && $company->subscribed('default') && !$company->subscription('default')->onGracePeriod())
                    <div class="mt-8 text-center">
                        <form method="POST" action="{{ route('billing.cancel') }}" onsubmit="return confirm('Are you sure you want to cancel your subscription?');">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded hover:bg-red-700">
                                Cancel Subscription
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</x-app-layout>
