<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Select Location for Boarding
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-md mx-auto">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <form method="POST" action="{{ route('boarding.location.set') }}">
                @csrf

                <label for="location_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Location:</label>
                <select id="location_id" name="location_id" required class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white p-2 mb-4">
                    <option value="">-- Choose a location --</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->name }} ({{ $location->city ?? '' }})</option>
                    @endforeach
                </select>

                @error('location_id')
                    <p class="text-red-600 text-sm mb-4">{{ $message }}</p>
                @enderror

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Select Location
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
