<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Loyalty Program Settings
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-2xl">
        @if (session('success'))
            <div class="mb-4 text-green-600 dark:text-green-400 font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('companies.loyalty-program.save') }}">
            @csrf

            <div class="mb-4">
                <label for="points_per_dollar" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                    Points Earned per $1 Spent
                </label>
                <input id="points_per_dollar" name="points_per_dollar" type="number" step="0.01" min="0"
                       value="{{ old('points_per_dollar', $program->points_per_dollar ?? '') }}"
                       class="mt-1 block w-full"
                       style="background-color: #fff; color: #000;" />
            </div>

            <div class="mb-4">
                <label for="point_value" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                    Value of Each Point (in dollars)
                </label>
                <input id="point_value" name="point_value" type="number" step="0.0001" min="0"
                       value="{{ old('point_value', $program->point_value ?? '') }}"
                       class="mt-1 block w-full"
                       style="background-color: #fff; color: #000;" />
            </div>

            <div class="mb-4">
                <label for="max_discount_percent" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                    Maximum Discount (% of Purchase)
                </label>
                <input id="max_discount_percent" name="max_discount_percent" type="number" step="0.01" min="0" max="100"
                       value="{{ old('max_discount_percent', $program->max_discount_percent ?? '') }}"
                       class="mt-1 block w-full"
                       style="background-color: #fff; color: #000;" />
            </div>

            <div class="mt-6">
                <x-primary-button>Save Settings</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
