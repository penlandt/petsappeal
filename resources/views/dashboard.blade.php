<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        {{-- 2-column grid for upcoming items --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Upcoming Grooming Appointments --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Upcoming Grooming Appointments</h3>

                    @if($upcomingAppointments->isEmpty())
                        <p class="text-gray-600 dark:text-gray-300">No upcoming appointments.</p>
                    @else
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($upcomingAppointments as $appt)
                                <li class="py-2 text-sm text-gray-800 dark:text-gray-200">
                                    <strong>{{ $appt->start_time->format('Y-m-d g:i A') }}</strong><br>
                                    {{ $appt->client->first_name }} {{ $appt->client->last_name }} —
                                    {{ $appt->pet->name }} —
                                    {{ $appt->service->name }}
                                    @if($appt->staff)
                                        ({{ $appt->staff->first_name }})
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            {{-- Upcoming Boarding Reservations --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Upcoming Boarding Reservations</h3>

                    @if($upcomingReservations->isEmpty())
                        <p class="text-gray-600 dark:text-gray-300">No upcoming reservations.</p>
                    @else
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($upcomingReservations as $reservation)
                                <li class="py-2 text-sm text-gray-800 dark:text-gray-200">
                                    <strong>{{ $reservation->check_in_date }} – {{ $reservation->check_out_date }}</strong><br>
                                    {{ $reservation->client->first_name }} {{ $reservation->client->last_name }}
                                    @if($reservation->pets->isNotEmpty())
                                        — {{ $reservation->pets->pluck('name')->join(', ') }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        {{-- Quick Links below the grid --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('clients.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">View Clients</a></li>
                    <li><a href="{{ route('pets.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">View Pets</a></li>
                    <li><a href="{{ route('services.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">View Services</a></li>
                    <li><a href="{{ route('schedule.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Open Grooming Schedule</a></li>
                    <li><a href="{{ route('boarding.reservations.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Open Boarding Module</a></li>
                    <li><a href="{{ route('pos.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Point of Sale</a></li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
