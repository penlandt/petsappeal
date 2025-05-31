<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Select Location
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl mx-auto bg-white dark:bg-gray-800 shadow-sm rounded p-6">
            @if (session('error'))
                <div class="mb-4 text-red-600 dark:text-red-400 font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('select-location.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="location_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Choose Your Location</label>
                    <select name="location_id" id="location_id" required class="mt-1 block w-full rounded shadow-sm"
                        style="background-color: #fff; color: #000;">
                        <option value="">-- Select a Location --</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}">
                                {{ $location->name }} ({{ $location->city }}, {{ $location->state }} {{ $location->postal_code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700">
                        Continue
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
