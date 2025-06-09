<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Approve Appointment for {{ $appointment->pet->name }}
        </h2>
    </x-slot>

    <div x-data="{ showDecline: false }" class="py-6 px-4 sm:px-6 lg:px-8 max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded shadow">
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Approve Form -->
        <form method="POST" action="{{ route('appointments.approval.update', $appointment->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Client</label>
                <div class="text-indigo-600 dark:text-indigo-300">
                    <a href="{{ route('clients.show', $appointment->pet->client_id) }}" class="underline">
                        {{ $appointment->pet->client->first_name }} {{ $appointment->pet->client->last_name }}
                    </a>
                </div>
            </div>

            <div class="mb-4">
                <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Pet</label>
                <div class="text-indigo-600 dark:text-indigo-300">
                    <a href="{{ route('pets.show', $appointment->pet_id) }}" class="underline">
                        {{ $appointment->pet->name }}
                    </a>
                </div>
            </div>

            <div class="mb-4">
                <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Service</label>
                <div class="text-gray-900 dark:text-gray-100">{{ $appointment->service->name }}</div>
            </div>

            <div class="mb-4">
                <label for="date" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Date</label>
                <input type="date" id="date" name="date" value="{{ old('date', $appointment->date) }}"
                       required class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600">
            </div>

            <div class="mb-4">
                <label for="time" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Time</label>
                <input type="time" id="time" name="time" value="{{ old('time', $appointment->time) }}"
                       required class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600">
            </div>

            <div class="mb-4">
                <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Client Notes</label>
                <div class="text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $appointment->notes ?? '—' }}</div>
            </div>

            <div class="mb-6">
                <label for="staff_id" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Assign to Staff Member</label>
                <select name="staff_id" id="staff_id" required class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    <option value="">-- Select Staff Member --</option>
                    @foreach ($staff as $person)
                        <option value="{{ $person->id }}" {{ old('staff_id', $appointment->staff_id) == $person->id ? 'selected' : '' }}>
                            {{ $person->first_name }} {{ $person->last_name }} ({{ $person->job_title }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-between">
                <button type="button" @click="showDecline = true"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded">
                    Decline
                </button>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded">
                    Approve Appointment
                </button>
            </div>
        </form>

        <!-- Decline Modal -->
        <div
            x-show="showDecline"
            x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div @click.away="showDecline = false" class="bg-white dark:bg-gray-800 p-6 rounded shadow-lg max-w-md w-full">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Decline Appointment</h2>
                <form method="POST" action="{{ url('/appointments/approval/' . $appointment->id . '/decline') }}">
                    @csrf
                    @method('DELETE')

                    <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason (optional):</label>
                    <textarea id="reason" name="reason" rows="3"
                              class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 mb-4"></textarea>

                    <div class="flex justify-between">
                        <button type="button" @click="showDecline = false"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded">
                            Decline Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
