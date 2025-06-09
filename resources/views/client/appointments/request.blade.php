<x-client-layout>
    <x-slot name="title">Request Appointment</x-slot>

    <div class="max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">Request an Appointment</h1>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('client.appointments.store') }}">
            @csrf

            {{-- Location --}}
            @if($locations->count() === 1)
                <input type="hidden" name="location_id" value="{{ $locations->first()->id }}">
            @else
                <div class="mb-4">
                    <label for="location_id" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Select Location</label>
                    <select name="location_id" id="location_id" required class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        <option value="">-- Choose Location --</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }} ({{ $location->city }}, {{ $location->state }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Pet --}}
            <div class="mb-4">
                <label for="pet_id" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Select Pet</label>
                <select name="pet_id" id="pet_id" required class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    <option value="">-- Choose Pet --</option>
                    @foreach($pets as $pet)
                        <option value="{{ $pet->id }}" {{ old('pet_id') == $pet->id ? 'selected' : '' }}>
                            {{ $pet->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Service --}}
            <div class="mb-4">
                <label for="service_id" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Select Service</label>
                <select name="service_id" id="service_id" required class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    <option value="">-- Choose Service --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }} ({{ $service->duration }} min, ${{ number_format($service->price, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Date --}}
            <div class="mb-4">
                <label for="date" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Preferred Date</label>
                <input type="date" id="date" name="date" required class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600" value="{{ old('date') }}">
            </div>

            {{-- Time --}}
            <div class="mb-4">
                <label for="time" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Preferred Time</label>
                <input type="time" id="time" name="time" required class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600" value="{{ old('time') }}">
            </div>

            {{-- Notes --}}
            <div class="mb-4">
                <label for="notes" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Additional Notes</label>
                <textarea name="notes" id="notes" rows="4" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Submit Request
            </button>
        </form>
    </div>
</x-client-layout>
