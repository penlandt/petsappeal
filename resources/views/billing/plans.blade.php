<x-app-layout>
    @if(request('cancelled'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            Subscription process was cancelled.
        </div>
    @endif

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Choose a Plan') }}
        </h2>
    </x-slot>

    <div class="py-10" x-data="{ annual: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Billing Toggle -->
            <div class="flex justify-center mb-8">
                <label class="mr-4 font-medium text-gray-700 dark:text-gray-300">Billing:</label>
                <button
                    @click="annual = false"
                    :class="annual ? 'bg-gray-300 dark:bg-gray-700 text-gray-800' : 'bg-blue-600 text-white'"
                    class="px-4 py-2 rounded-l-md focus:outline-none"
                >
                    Monthly
                </button>
                <button
                    @click="annual = true"
                    :class="annual ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-700 text-gray-800'"
                    class="px-4 py-2 rounded-r-md focus:outline-none"
                >
                    Annual
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($plans as $plan)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 flex flex-col justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $plan['name'] }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 text-lg my-2">
                                <span x-show="!annual">${{ $plan['monthly_price'] }}/month</span>
                                <span x-show="annual" x-cloak>${{ $plan['annual_price'] }}/year</span>
                            </p>
                            <ul class="mb-4 text-sm text-gray-700 dark:text-gray-300">
                                @foreach ($plan['features'] as $feature)
                                    <li class="mb-1">• {{ $feature }}</li>
                                @endforeach
                            </ul>
                        </div>

                        @auth
                            <form method="POST" action="{{ route('billing.checkout') }}">
                                @csrf
                                <input type="hidden"
                                       name="price_id"
                                       :value="annual ? '{{ $plan['annual_price_id'] }}' : '{{ $plan['monthly_price_id'] }}'">
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
        </div>
    </div>
</x-app-layout>
