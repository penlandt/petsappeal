<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Add Boarding Unit
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-sm rounded p-6">
            <form method="POST" action="{{ route('boarding.units.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Name</label>
                    <input name="name" type="text" required
                           class="mt-1 block w-full rounded-md shadow-sm"
                           style="background-color: #fff; color: #000;"
                           value="{{ old('name') }}" />
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Type</label>
                    <select name="type" required class="mt-1 block w-full rounded-md shadow-sm"
                            style="background-color: #fff; color: #000;">
                        <option value="">-- Select Type --</option>
                        <option value="kennel">Kennel</option>
                        <option value="cage">Cage</option>
                        <option value="room">Room</option>
                        <option value="unit">Unit</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Size</label>
                    <select name="size" required class="mt-1 block w-full rounded-md shadow-sm"
                            style="background-color: #fff; color: #000;">
                        <option value="">-- Select Size --</option>
                        <option value="small">Small</option>
                        <option value="medium">Medium</option>
                        <option value="large">Large</option>
                        <option value="extra-large">Extra-Large</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Max Occupants</label>
                    <input name="max_occupants" type="number" min="1" required
                           class="mt-1 block w-full rounded-md shadow-sm"
                           style="background-color: #fff; color: #000;"
                           value="{{ old('max_occupants', 1) }}" />
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Price per Night</label>
                    <input name="price_per_night" type="number" step="0.01" required
                           class="mt-1 block w-full rounded-md shadow-sm"
                           style="background-color: #fff; color: #000;"
                           value="{{ old('price_per_night') }}" />
                </div>

                <div class="mb-6">
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Location</label>
                    <select name="location_id" required class="mt-1 block w-full rounded-md shadow-sm"
                            style="background-color: #fff; color: #000;">
                        <option value="">-- Select Location --</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}"
                                {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }} ({{ $location->city }}, {{ $location->state }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('boarding.units.index') }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Cancel
                    </a>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Save Boarding Unit
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
