<x-app-layout>
    @if(request('cancelled'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            Subscription process was cancelled.
        </div>
    @endif

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Choose Your Plan') }}
        </h2>
    </x-slot>

    <div class="py-10" x-data="planSelector()" x-init="init()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">

            <!-- Toggle for Billing Cycle -->
            <div class="text-center">
                <div class="inline-flex items-center space-x-2">
                    <label class="text-gray-700 dark:text-gray-300 font-medium">Billing:</label>
                    <button @click="annual = false; updateTotal()"
                        :class="!annual ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800'"
                        class="px-4 py-2 rounded-l-md focus:outline-none">Monthly</button>
                    <button @click="annual = true; updateTotal()"
                        :class="annual ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800'"
                        class="px-4 py-2 rounded-r-md focus:outline-none">Annual</button>
                </div>
            </div>

            <!-- Modules -->
            <h3 class="text-2xl font-bold text-center text-gray-800 dark:text-white">Choose Your Modules</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($plans as $plan)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col justify-between">
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $plan['name'] }}</h4>
                            <p class="text-2xl text-gray-800 dark:text-white font-semibold mb-4">
                                <span x-show="!annual">${{ $plan['monthly_price'] }}/mo</span>
                                <span x-show="annual" x-cloak>${{ $plan['annual_price'] }}/yr</span>
                            </p>
                            <ul class="text-gray-700 dark:text-gray-300 text-sm space-y-1 mb-4">
                                @foreach ($plan['features'] as $feature)
                                    <li>• {{ $feature }}</li>
                                @endforeach
                                <li>• POS Module Included</li>
                            </ul>
                        </div>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox"
                                value="{{ $plan['monthly_price_id'] }}"
                                x-bind:value="annual ? '{{ $plan['annual_price_id'] }}' : '{{ $plan['monthly_price_id'] }}'"
                                class="form-checkbox h-5 w-5 text-blue-600"
                                @change="toggleModule($event)">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Include {{ $plan['name'] }}</span>
                        </label>
                    </div>
                @endforeach
            </div>

            <!-- Support Tier -->
            <h3 class="text-2xl font-bold text-center text-gray-800 dark:text-white">Optional: Choose a Support Upgrade</h3>
            <p class="text-center text-gray-600 dark:text-gray-300 mb-4">
                Selecting a support level is completely optional. All plans include free basic email support.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                @foreach ($supportOptions as $option)
                    <div x-data="{ monthly: '{{ $option['monthly_price_id'] }}', annualId: '{{ $option['annual_price_id'] }}' }"
                         :class="{ 'ring-2 ring-blue-500': selectedSupport === (annual ? annualId : monthly) }"
                         class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex items-center space-x-4 cursor-pointer border border-transparent hover:border-blue-500"
                         @click="selectedSupport = annual ? annualId : monthly; updateTotal()">
                        <input type="radio"
                            name="support"
                            class="form-radio h-5 w-5 text-blue-600"
                            :value="annual ? annualId : monthly"
                            :checked="selectedSupport === (annual ? annualId : monthly)">
                        <div>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $option['name'] }}</div>
                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                <span x-show="!annual">${{ $option['monthly_price'] }}/mo</span>
                                <span x-show="annual" x-cloak>${{ $option['annual_price'] }}/yr</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Total and Checkout -->
            <div class="text-center mt-10">
                <div class="text-xl font-bold mb-4 text-gray-800 dark:text-white">
                    Total: <span x-text="totalFormatted"></span>
                </div>

                <form method="POST" action="{{ route('billing.checkout') }}" @submit.prevent="submitCheckout">
                    @csrf
                    <template x-for="(id, index) in selectedPriceIds" :key="index">
                        <input type="hidden" :name="`price_ids[${index}]`" :value="id">
                    </template>
                    <input type="hidden" name="support_price_id" :value="selectedSupport">
                    <x-primary-button class="text-white text-lg px-6 py-3">
                        Continue to Checkout
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function planSelector() {
        return {
            annual: false,
            selectedPriceIds: [],
            selectedSupport: null,
            totalFormatted: '$0/mo',

            priceLookup: {
                '{{ config('services.stripe.modules.grooming.monthly') }}': 49,
                '{{ config('services.stripe.modules.grooming.annual') }}': 499,
                '{{ config('services.stripe.modules.boarding.monthly') }}': 49,
                '{{ config('services.stripe.modules.boarding.annual') }}': 499,
                '{{ config('services.stripe.modules.daycare.monthly') }}': 49,
                '{{ config('services.stripe.modules.daycare.annual') }}': 499,
                '{{ config('services.stripe.modules.sitting.monthly') }}': 49,
                '{{ config('services.stripe.modules.sitting.annual') }}': 499,
                '{{ config('services.stripe.support.chat.monthly') }}': 49,
                '{{ config('services.stripe.support.chat.annual') }}': 499,
                '{{ config('services.stripe.support.phone.monthly') }}': 99,
                '{{ config('services.stripe.support.phone.annual') }}': 999,
            },

            init() {
                console.log('[INIT] Running init()');
                this.updateTotal();
            },

            toggleModule(e) {
                const val = e.target.value;
                if (e.target.checked) {
                    if (!this.selectedPriceIds.includes(val)) {
                        console.log(`[MODULE] Adding ${val}`);
                        this.selectedPriceIds.push(val);
                    }
                } else {
                    console.log(`[MODULE] Removing ${val}`);
                    this.selectedPriceIds = this.selectedPriceIds.filter(id => id !== val);
                }
                console.log('[MODULE] Current selectedPriceIds:', this.selectedPriceIds);
                this.updateTotal();
            },

            updateTotal() {
                const combined = [...this.selectedPriceIds];
                if (this.selectedSupport !== null) {
                    combined.push(this.selectedSupport);
                }

                const uniqueIds = Array.from(new Set(combined));
                console.log('[TOTAL] uniqueIds:', uniqueIds);
                console.log('[TOTAL] priceLookup:', this.priceLookup);

                const total = uniqueIds.reduce((sum, id) => {
                    const amount = this.priceLookup[id] || 0;
                    console.log(` - ${id}: $${amount}`);
                    return sum + amount;
                }, 0);

                this.totalFormatted = this.annual ? `$${total}/yr` : `$${total}/mo`;
                console.log('[TOTAL] Updated totalFormatted:', this.totalFormatted);
            },

            submitCheckout(e) {
                console.log('[SUBMIT] selectedPriceIds:', this.selectedPriceIds);
                console.log('[SUBMIT] selectedSupport:', this.selectedSupport);
                if (this.selectedPriceIds.length === 0) {
                    alert("Please select at least one module.");
                    return;
                }
                e.target.submit();
            }
        };
    }
    </script>
</x-app-layout>
