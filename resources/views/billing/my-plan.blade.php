<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            My Plan
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    Subscription Details
                </h3>

                @if ($onTrial && !$subscription)
                    <p class="text-yellow-500 mb-2">
                        Your company is currently on a free trial.
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Trial ends on: <strong>{{ \Carbon\Carbon::parse($trialEndsAt)->toFormattedDateString() }}</strong>
                    </p>
                    <a href="{{ route('billing.plans') }}"
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition">
                        Choose a Subscription Plan
                    </a>

                @elseif ($subscription && $subscription->valid())
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Plan: <strong>{{ $plan ?? 'Unknown Plan' }}</strong>
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Status: <strong class="capitalize">{{ $subscription->stripe_status }}</strong>
                        </p>

                        @if ($subscription->onGracePeriod())
                            <p class="text-sm text-red-500">
                                Subscription is canceled but still active until {{ $subscription->ends_at->toFormattedDateString() }}.
                            </p>
                        @endif

                        @if ($onTrial)
                            <p class="text-sm text-yellow-500">
                                Trial ends: {{ \Carbon\Carbon::parse($trialEndsAt)->toFormattedDateString() }}
                            </p>
                        @endif

                        @if ($endsAt && !$subscription->onGracePeriod())
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Renews on: {{ $endsAt->toFormattedDateString() }}
                            </p>
                        @endif
                    </div>

                @else
                    <p class="text-red-500 mb-4">Your company does not currently have an active subscription.</p>
                    <a href="{{ route('billing.plans') }}"
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition">
                        View Available Plans
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
