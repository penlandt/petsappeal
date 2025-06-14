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

                    {{-- Cancel Subscription Button --}}
                    <div class="mt-6 space-y-4">
                        <form method="POST" action="{{ route('billing.cancel-subscription') }}"
                              onsubmit="return confirm('Are you sure you want to cancel your subscription?');">
                            @csrf
                            <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded transition">
                                Cancel Subscription
                            </button>
                        </form>

                        @php
                            $supportPriceIds = [
                                config('services.stripe.support.chat.monthly'),
                                config('services.stripe.support.chat.annual'),
                                config('services.stripe.support.phone.monthly'),
                                config('services.stripe.support.phone.annual'),
                            ];
                        @endphp

                        @if (!empty($subscription->stripe_price) && collect(json_decode($subscription->stripe_price))->intersect($supportPriceIds)->isNotEmpty())
                            <form method="POST" action="{{ route('billing.downgrade-subscription') }}"
                                  onsubmit="return confirm('This will remove support from your subscription. Continue?');">
                                @csrf
                                <button type="submit"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded transition">
                                    Downgrade (Remove Support)
                                </button>
                            </form>
                        @endif

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Canceling will stop renewal at the end of your billing cycle. You will retain full access until then.
                            <strong>No refunds will be issued.</strong>
                        </p>
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
