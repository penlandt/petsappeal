<x-app-layout>
    <x-slot name="header">
        @if (session('success'))
            <div class="mb-4 text-green-600 dark:text-green-400 font-semibold whitespace-pre-line">
                {!! nl2br(e(session('success'))) !!}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-800 rounded">
                <strong>There was a problem saving the appointment:</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-wrap justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Grooming Module
            </h2>
            @include('modules.grooming.partials.secondary-menu')
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
    @if (!$canSchedule)
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded mb-6 dark:bg-yellow-200 dark:text-yellow-900">
        <p class="font-semibold">Schedule not available</p>
        <p>You must create at least one
            <a href="{{ route('locations.create') }}" class="underline text-blue-600 hover:text-blue-800">Location</a>
            and one
            <a href="{{ route('staff.create') }}" class="underline text-blue-600 hover:text-blue-800">Staff Member</a>
            before using the schedule.
        </p>
    </div>
@endif

@if ($canSchedule)

        <div class="flex justify-between items-end mb-6 gap-4 flex-wrap">
            <!-- Location Picker -->
            <form method="GET" action="{{ route('schedule.index') }}">
                <label for="location_id" class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">
                    Location
                </label>
                <select name="location_id"
                        id="location_id"
                        onchange="this.form.submit()"
                        class="block w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-black dark:text-white p-2">
                    @foreach ($locations->where('inactive', false) as $location)
                        <option value="{{ $location->id }}" {{ $location->id == $selectedLocationId ? 'selected' : '' }}>
                            {{ $location->name }} — {{ $location->city }}, {{ $location->state }}
                        </option>
                    @endforeach
                </select>
            </form>
            <!-- Date Picker -->
            <div>
                <label for="datepicker" class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                <input type="text" id="datepicker" class="border-gray-300 rounded p-2 w-64" style="background-color: #fff; color: #000;" />
            </div>
        </div>

        <!-- Calendar Placeholder -->
        <div id="calendarWrapper">
            <div id="calendar" class="mt-6"></div>
        </div>
        <!-- Appointment Status Color Key -->
        <div class="mt-8">
            <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-2">Appointment Status Color Key:</h3>
            <div class="flex flex-wrap gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded" style="background-color: #4b5563;"></div>
                    <span class="text-sm text-gray-800 dark:text-gray-200">Booked</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded" style="background-color: #2563eb;"></div>
                    <span class="text-sm text-gray-800 dark:text-gray-200">Confirmed</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded" style="background-color: #b91c1c;"></div>
                    <span class="text-sm text-gray-800 dark:text-gray-200">Cancelled</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded" style="background-color: #92400e;"></div>
                    <span class="text-sm text-gray-800 dark:text-gray-200">No-Show</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded" style="background-color: #059669;"></div>
                    <span class="text-sm text-gray-800 dark:text-gray-200">Checked In</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded" style="background-color: #6b21a8;"></div>
                    <span class="text-sm text-gray-800 dark:text-gray-200">Checked Out</span>
                </div>
            </div>
        </div>

    </div>

<!-- New Appointment Modal -->
    <div id="appointmentModal"
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60"
         style="z-index: 10000; display: none;">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-lg w-full max-w-xl">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Schedule Appointment</h3>
        <form id="appointmentForm" method="POST" action="{{ route('appointments.store') }}">
            @csrf
            <input type="hidden" id="start_time" name="start_time">
            <input type="hidden" name="date" value="{{ $selectedDate }}">
            <input type="hidden" name="location_id" value="{{ $selectedLocationId }}">

            <div class="mb-4">
                <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client</label>
                <select id="client_id" name="client_id" class="..." style="background-color: #fff; color: #000;">
                    <option value="">-- Select a client --</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">
                            {{ $client->first_name }} {{ $client->last_name }} ({{ $client->phone }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="pet_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pet</label>
                <select id="pet_id" name="pet_id" class="w-full border border-gray-300 rounded p-2" style="background-color: #fff; color: #000;"></select>
            </div>

            <div class="mb-4">
                <label for="service_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service</label>
                <select id="service_id" name="service_id" class="..." style="background-color: #fff; color: #000;">
                    <option value="">-- Select a service --</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">
                            {{ $service->name }} - ${{ number_format($service->price, 2) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                <input type="text" id="price" name="price" class="w-full border border-gray-300 rounded p-2" style="background-color: #fff; color: #000;" />
            </div>

            <div class="mb-4">
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                <textarea id="notes" name="notes" rows="3" class="w-full border border-gray-300 rounded p-2" style="background-color: #fff; color: #000;"></textarea>
            </div>

            <!-- Recurring Appointment Settings -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">Repeat Appointment?</label>
                <select name="recurrence_type" id="recurrence_type" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">No</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>

            <div class="mt-4">
                <label for="recurrence_interval" class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">Repeat Every...</label>
                <input type="number" min="1" max="12" name="recurrence_interval" id="recurrence_interval" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Enter number of weeks or months">
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">For weekly recurrence, enter 1–4. For monthly, enter 1–12.</p>
            </div>


            <div class="flex justify-end">
                <button type="button" onclick="closeAppointmentModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Cancel</button>
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- New Client Modal -->
<div id="newClientModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-lg w-full max-w-xl z-50 relative">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Add New Client</h3>
        <form id="newClientForm">
            <div class="mb-4">
                <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">First Name</label>
                <input type="text" id="first_name" name="first_name" class="w-full border border-gray-300 rounded p-2" style="background-color: #fff; color: #000;">
            </div>

            <div class="mb-4">
                <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="w-full border border-gray-300 rounded p-2" style="background-color: #fff; color: #000;">
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number</label>
                <input type="text" id="phone" name="phone" class="w-full border border-gray-300 rounded p-2" style="background-color: #fff; color: #000;">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded p-2" style="background-color: #fff; color: #000;">
            </div>

            <div class="flex justify-end">
                <button type="button" id="cancelNewClientBtn" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Client</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Appointment Modal -->
<div id="editAppointmentModal"
     class="hidden fixed z-[9999] bg-black bg-opacity-60"
     style="
        top: 50%;
        transform: translate(-50%, -50%);
         left: 50%;
         transform: translate(-50%, -50%);
         margin: 0;
         padding: 0;
         width: 100vw;
         height: 100vh;
     ">

<div class="bg-white dark:bg-gray-800 text-black dark:text-white rounded-lg shadow-xl p-4"
     style="
         position: absolute;
         top: 50%;
         left: 50%;
         transform: translate(-50%, -50%);
         width: 100%;
         max-width: 600px;
         min-width: 400px;
         max-height: 90vh;
         overflow-y: auto;
     ">



<form id="editAppointmentForm" method="POST">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="edit_appointment_id" name="appointment_id">

            <div class="mb-2">
                <label for="edit_appointment_date" class="block text-sm font-medium mb-1">Appointment Date</label>
                <input type="date" id="edit_appointment_date" name="appointment_date"
                    class="w-full border rounded p-1"
                    style="background-color: #fff; color: #000;">
            </div>

            <div class="mb-2">
                <label for="edit_start_time" class="block text-sm font-medium mb-1">Start Time</label>
                <input type="time" id="edit_start_time" name="start_time"
                    class="w-full border rounded p-1"
                    style="background-color: #fff; color: #000;">
            </div>

            <div class="mb-2">
                <label for="edit_staff_id" class="block text-sm font-medium mb-1">Staff</label>
                <select id="edit_staff_id" name="staff_id"
                        class="w-full border rounded p-1"
                        style="background-color: #fff; color: #000;">
                    @foreach ($staff as $person)
                        <option value="{{ $person->id }}">
                            {{ $person->first_name }} {{ $person->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2">
                <label for="edit_client_id" class="block text-sm font-medium mb-1">Client</label>
                <select id="edit_client_id" name="client_id"
                        class="w-full border rounded p-1"
                        style="background-color: #fff; color: #000;">
                    <option value="">-- Select a client --</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">
                            {{ $client->first_name }} {{ $client->last_name }} ({{ $client->phone }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2">
                <label for="edit_pet_id" class="block text-sm font-medium mb-1">Pet</label>
                <select id="edit_pet_id" name="pet_id"
                        class="w-full border rounded p-1"
                        style="background-color: #fff; color: #000;"></select>
            </div>

            <div class="mb-2">
                <label for="edit_service_id" class="block text-sm font-medium mb-1">Service</label>
                <select id="edit_service_id" name="service_id"
                        class="w-full border rounded p-1"
                        style="background-color: #fff; color: #000;"></select>
            </div>

            <div class="mb-2">
                <label for="edit_price" class="block text-sm font-medium mb-1">Price</label>
                <input type="text" id="edit_price" name="price"
                       class="w-full border rounded p-1"
                       style="background-color: #fff; color: #000;">
            </div>

            <div class="mb-2">
                <label for="edit_notes" class="block text-sm font-medium mb-1">Notes</label>
                <textarea id="edit_notes" name="notes" rows="3"
                          class="w-full border rounded p-1"
                          style="background-color: #fff; color: #000;"></textarea>
            </div>

            <div class="mb-2">
                <label for="edit_status" class="block text-sm font-medium mb-1">Status</label>
                <select id="edit_status" name="status"
                        class="w-full border rounded p-1"
                        style="background-color: #fff; color: #000;">
                    <option value="Booked">Booked</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Cancelled">Cancelled</option>
                    <option value="No-Show">No-Show</option>
                    <option value="Checked In">Checked In</option>
                    <option value="Checked Out">Checked Out</option>
                </select>
            </div>

            <!-- Apply Changes To -->
            <div class="mt-6" id="edit_recurrence_options" style="display: none;">
                <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">Apply changes to</label>
                <select name="apply_to_series" id="apply_to_series" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="single">This appointment only</option>
                    <option value="future">All future appointments in this series</option>
                </select>
            </div>


            <div class="flex justify-end mt-6 space-x-2">
                <button type="button"
                    onclick="document.getElementById('editAppointmentModal').classList.add('hidden'); document.getElementById('calendarWrapper').style.display = 'block';"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Cancel
                </button>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Save
                </button>
            </div>

        </form>
    </div>
</div>

<div id="calendar-tooltip" class="tooltip-box"></div>

@push('scripts')
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css" rel="stylesheet">

<!-- FullCalendar Combined JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.11/index.global.min.js"></script>

<!-- TomSelect CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

<!-- TomSelect JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
/* Resource (staff) header fix */
.fc .fc-resource-header,
.fc .fc-col-header-cell-cushion {
    background-color: #1f2937 !important;
    color: #ffffff !important;
    font-weight: bold;
}

/* Main column header row fix (dates) */
.fc .fc-col-header-cell {
    background-color: #1f2937 !important;
    color: #ffffff !important;
    font-weight: bold;
    border: 1px solid #374151; /* Tailwind gray-700 */
}
</style>

<style>
    .tooltip-box {
    position: absolute;
    background-color: #1f2937; /* dark gray */
    color: white;
    padding: 8px 10px;
    border-radius: 6px;
    font-size: 0.75rem;
    z-index: 99999;
    white-space: nowrap;
    pointer-events: none;
    display: none;
    max-width: 300px;
}
</style>

<script>
    const servicePriceMap = {
        @foreach ($services as $service)
            "{{ $service->id }}": {{ $service->price }},
        @endforeach
    };

    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        window.calendar = new FullCalendar.Calendar(calendarEl, {
            schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
            timeZone: '{{ $defaultLocation->time_zone }}',
            slotEventOverlap: true,
            eventOverlap: true,
            initialView: 'resourceTimeGridDay',
            resourceAreaHeaderContent: 'Staff',
            initialDate: '{{ $selectedDate }}',
            scrollTime: '{{ \Carbon\Carbon::now()->format('H:i:s') }}',
            nowIndicator: true,
            height: 'auto',
            allDaySlot: false,
            slotMinTime: "07:00:00",
            slotMaxTime: "20:00:00",

            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'resourceTimeGridDay,resourceTimeGridWeek'
            },

            titleFormat: { weekday: 'long', month: 'short', day: 'numeric', year: 'numeric' },

            selectable: true,
            resources: {!! $staff->map(function ($s) {
                return [
                    'id' => $s->id,
                    'title' => $s->first_name . ' ' . $s->last_name,
                ];
            })->toJson() !!},

            eventSources: [
                {
                    url: '/api/appointments',
                    method: 'GET',
                    failure: function () {
                        alert('there was an error while fetching appointments!');
                    }
                },
                {
                    events: {!! json_encode($backgroundEvents) !!}
                }
            ],

            eventDidMount: function(info) {
                const status = info.event.extendedProps.status;

                let bgColor = '';
                switch (status) {
                    case 'Confirmed':
                        bgColor = '#2563eb'; // blue-600
                        break;
                    case 'Cancelled':
                        bgColor = '#b91c1c'; // red-700
                        break;
                    case 'No-Show':
                        bgColor = '#92400e'; // amber-800
                        break;
                    case 'Checked In':
                        bgColor = '#059669'; // green-600
                        break;
                    case 'Checked Out':
                        bgColor = '#6b21a8'; // purple-800
                        break;
                    default:
                        bgColor = '#4b5563'; // default gray-600 for "Booked" or unknown
                }

                info.el.style.backgroundColor = bgColor;
                info.el.style.borderColor = bgColor;

                // Tooltip logic
                const tooltip = document.getElementById('calendar-tooltip');
                const appt = info.event.extendedProps;

                info.el.addEventListener('mouseenter', (e) => {
                    tooltip.innerHTML = `
                        <strong>${info.event.title}</strong><br>
                        <span>Client: ${appt.client_name || 'N/A'}</span><br>
                        <span>Phone: ${appt.client_phone || 'N/A'}</span><br>
                        <span>Service: ${appt.service_name || 'N/A'}</span><br>
                        <span>Status: ${appt.status}</span><br>
                        <span>Notes: ${appt.notes ? appt.notes.replace(/\n/g, '<br>') : 'None'}</span>
                    `;
                    tooltip.style.display = 'block';
                    tooltip.style.left = `${e.pageX + 10}px`;
                    tooltip.style.top = `${e.pageY + 10}px`;
                });

                info.el.addEventListener('mousemove', (e) => {
                    tooltip.style.left = `${e.pageX + 10}px`;
                    tooltip.style.top = `${e.pageY + 10}px`;
                });

                info.el.addEventListener('mouseleave', () => {
                    tooltip.style.display = 'none';
                });
            },



            eventClick: function(info) {
                const appointmentId = info.event.id;

                console.log('Fetching:', `/api/appointments/${appointmentId}`);

                fetch(`/api/appointments/${appointmentId}`)
                    .then(async response => {
                        const text = await response.text();
                        console.log('Raw response:', text);

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = JSON.parse(text);
                        console.log('Parsed JSON:', data);

                        const appt = data.appointment;

                        // Show recurrence options if part of a series
                        if (appt.recurrence_group_id) {
                            document.getElementById('edit_recurrence_options').style.display = 'block';
                        } else {
                            document.getElementById('edit_recurrence_options').style.display = 'none';
                        }

                        if (window.editClientSelect) {
                            window.editClientSelect.destroy();
                        }

                        window.editClientSelect = new TomSelect('#edit_client_id', {
                            create: false,
                            placeholder: '-- Select a client --',
                            sortField: {
                                field: "text",
                                direction: "asc"
                            },
                            onChange: function(value) {
                                const petSelect = document.getElementById('edit_pet_id');
                                petSelect.innerHTML = '';

                                const placeholder = document.createElement('option');
                                placeholder.value = '';
                                placeholder.textContent = '-- Select a pet --';
                                petSelect.appendChild(placeholder);

                                if (!value) return;

                                fetch(`/api/clients/${value}/pets`)
                                    .then(response => response.json())
                                    .then(pets => {
                                        pets.forEach(pet => {
                                            const option = new Option(`${pet.name} (${pet.species})`, pet.id);
                                            petSelect.appendChild(option);
                                        });

                                        petSelect.value = appt.pet_id;
                                    });
                            }
                        });

                        if (window.editClientSelect) {
                            window.editClientSelect.destroy();
                        }

                        // Get the raw select element
                        const rawClientSelect = document.getElementById('edit_client_id');

                        // Clear existing options (in case TomSelect was already used)
                        rawClientSelect.innerHTML = '';

                        // Build and insert the <option> using client name and phone
                        if (
                            data.client &&
                            data.client.id &&
                            data.client.first_name &&
                            data.client.last_name &&
                            data.client.phone
                        ) {
                            const clientLabel = `${data.client.first_name} ${data.client.last_name} (${data.client.phone})`;
                            const option = new Option(clientLabel, data.client.id, true, true);
                            rawClientSelect.appendChild(option);
                        }

                        // Now initialize TomSelect on the populated dropdown
                        window.editClientSelect = new TomSelect('#edit_client_id', {
                            create: false,
                            placeholder: '-- Select a client --',
                            sortField: {
                                field: "text",
                                direction: "asc"
                            },
                            onChange: function(value) {
                                const petSelect = document.getElementById('edit_pet_id');
                                petSelect.innerHTML = '';

                                const placeholder = new Option('-- Select a pet --', '');
                                petSelect.appendChild(placeholder);

                                if (!value) return;

                                fetch(`/api/clients/${value}/pets`)
                                    .then(response => response.json())
                                    .then(pets => {
                                        pets.forEach(pet => {
                                            const option = new Option(`${pet.name} (${pet.species})`, pet.id);
                                            petSelect.appendChild(option);
                                        });

                                        // Pre-select the pet from the appointment
                                        petSelect.value = appt.pet_id;
                                    });
                            }
                        });

                        const petSelect = document.getElementById('edit_pet_id');
                        petSelect.innerHTML = '';

                        const placeholder = new Option('-- Select a pet --', '');
                        petSelect.appendChild(placeholder);

                        if (data.client && data.client.id) {
                            fetch(`/api/clients/${data.client.id}/pets`)
                                .then(response => response.json())
                                .then(pets => {
                                    pets.forEach(pet => {
                                        const option = new Option(`${pet.name} (${pet.species})`, pet.id);
                                        petSelect.appendChild(option);
                                    });

                                    petSelect.value = appt.pet_id;
                                })
                                .catch(error => {
                                    console.error('Failed to load pets for client:', error);
                                });
                        }


                        if (window.editServiceSelect) {
                            window.editServiceSelect.destroy();
                        }

                        const rawServiceSelect = document.getElementById('edit_service_id');
                        rawServiceSelect.innerHTML = '';

                        const servicePlaceholder = new Option('-- Select a service --', '');
                        rawServiceSelect.appendChild(servicePlaceholder);

                        // These should already be available to you (preloaded in the DOM)
                        const serviceOptions = [
                            { id: 1, label: 'Full Service Grooming (Large, Full-Coat) - $125.00' },
                            { id: 2, label: 'Bath & Brush Plus (Large, Full-Coat) - $75.00' },
                            { id: 3, label: 'Nail Clipping (Large) - $25.00' },
                            { id: 4, label: 'Teeth Brushing (Large) - $15.00' },
                            { id: 5, label: 'Nail Clipping (Bird, Medium) - $15.99' }
                        ];

                        // Populate dropdown
                        serviceOptions.forEach(service => {
                            const option = new Option(service.label, service.id);
                            rawServiceSelect.appendChild(option);
                        });

                        // Initialize TomSelect
                        window.editServiceSelect = new TomSelect('#edit_service_id', {
                            create: false,
                            placeholder: '-- Select a service --',
                            sortField: {
                                field: "text",
                                direction: "asc"
                            }
                        });

                        // Pre-select the current service
                        window.editServiceSelect.setValue(appt.service_id);

                        // Set the price **after** the service is selected
                        const priceField = document.getElementById('edit_price');
                        const selectedServiceId = String(appt.service_id);

                        if (selectedServiceId && servicePriceMap[selectedServiceId]) {
                            priceField.value = servicePriceMap[selectedServiceId].toFixed(2);
                        } else {
                            priceField.value = '';
                        }

                        document.getElementById('edit_appointment_id').value = appt.appointment_id;
                        document.getElementById('editAppointmentForm').action = `/appointments/${appt.appointment_id}`;
                        document.getElementById('edit_pet_id').value = appt.pet_id;
                        document.getElementById('edit_service_id').value = appt.service_id;
                        document.getElementById('edit_notes').value = appt.notes || '';
                        document.getElementById('edit_status').value = appt.status || 'Booked';

                        document.getElementById('edit_appointment_date').value = appt.start_time.substring(0, 10);
                        document.getElementById('edit_start_time').value = appt.start_time.substring(11, 16);
                        document.getElementById('edit_staff_id').value = appt.staff_id;

                        document.getElementById('editAppointmentModal').classList.remove('hidden');
                        document.getElementById('calendarWrapper').style.display = 'none';
                        document.getElementById('editAppointmentModal').classList.add('fixed', 'inset-0', 'z-50');
                    })
                    .catch(error => {
                        console.error('Error loading appointment:', error);
                        alert('Failed to load appointment for editing.');

                    });
            },




            dateClick: function(info) {
                console.log('dateClick triggered:', info);
                window.selectedStaffId = info.resource.id;

                const modal = document.getElementById('appointmentModal');

                modal.style.display = 'flex';
                modal.style.position = 'fixed';
                modal.style.top = '0';
                modal.style.left = '0';
                modal.style.width = '100%';
                modal.style.height = '100%';
                modal.style.backgroundColor = 'rgba(0, 0, 0, 0.6)';
                modal.style.zIndex = '9999';

                const form = document.getElementById('appointmentForm');
                form.reset();

                form.addEventListener('submit', function () {
                    console.log('Appointment form is submitting...');

                    form.querySelectorAll('input[name="appointment_date"], input[name="start_time"]').forEach(el => el.remove());

                    const dateInput = document.createElement('input');
                    dateInput.type = 'hidden';
                    dateInput.name = 'appointment_date';
                    dateInput.value = window.selectedAppointmentDate;
                    form.appendChild(dateInput);

                    const timeInput = document.createElement('input');
                    timeInput.type = 'hidden';
                    timeInput.name = 'start_time';
                    timeInput.value = window.selectedAppointmentTime;
                    form.appendChild(timeInput);

                    const staffInput = document.createElement('input');
                    staffInput.type = 'hidden';
                    staffInput.name = 'staff_id';
                    staffInput.value = window.selectedStaffId;
                    form.appendChild(staffInput);

                    const locationInput = document.createElement('input');
                    locationInput.type = 'hidden';
                    locationInput.name = 'location_id';
                    locationInput.value = '{{ $selectedLocationId }}';
                    form.appendChild(locationInput);
                });

                window.selectedAppointmentDate = info.dateStr.split('T')[0];
                window.selectedAppointmentTime = new Date(info.dateStr).toTimeString().slice(0, 5);

                if (window.clientSelect) {
                    window.clientSelect.destroy();
                }

                window.clientSelect = new TomSelect('#client_id', {
                    create: false,
                    placeholder: '-- Select a client --',
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    onChange: function(value) {
                        const form = document.querySelector('#appointmentModal form');
                        const petSelect = form.querySelector('#pet_id');

                        petSelect.innerHTML = '';
                        const placeholder = document.createElement('option');
                        placeholder.value = '';
                        placeholder.textContent = '-- Select a pet --';
                        petSelect.appendChild(placeholder);

                        if (!value) return;

                        fetch(`/api/clients/${value}/pets`)
                            .then(response => response.json())
                            .then(pets => {
                                pets.forEach(pet => {
                                    const option = document.createElement('option');
                                    option.value = pet.id;
                                    option.textContent = `${pet.name} (${pet.species})`;
                                    option.dataset.notes = pet.notes || '';
                                    petSelect.appendChild(option);
                                });

                                // Copy notes when a pet is selected
                                petSelect.addEventListener('change', function () {
                                    const selectedOption = petSelect.options[petSelect.selectedIndex];
                                    const petNotes = selectedOption.dataset.notes || '';
                                    document.getElementById('notes').value = petNotes;
                                });

                                // ✅ If a pet is added, pre-select the first and trigger notes
                                if (petSelect.options.length > 1) {
                                    petSelect.selectedIndex = 1;
                                    petSelect.dispatchEvent(new Event('change'));
                                }
                            })
                            .catch(error => {
                                console.error('Failed to load pets:', error);
                            });
                    }
                });

                if (window.serviceSelect) {
                    window.serviceSelect.destroy();
                }

                window.serviceSelect = new TomSelect('#service_id', {
                    create: false,
                    placeholder: '-- Select a service --',
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });

                document.querySelector('#service_id').addEventListener('change', function () {
                    const selectedServiceId = this.value;
                    const priceField = document.querySelector('#price');
                    priceField.value = selectedServiceId && servicePriceMap[selectedServiceId]
                        ? servicePriceMap[selectedServiceId].toFixed(2)
                        : '';
                });

                form.querySelector('#client_id').addEventListener('change', function () {
                    const clientId = this.value;
                    const petSelect = form.querySelector('#pet_id');
                    petSelect.innerHTML = '';

                    if (!clientId) return;

                    fetch(`/api/clients/${clientId}/pets`)
                        .then(response => response.json())
                        .then(pets => {
                            pets.forEach(pet => {
                                const option = document.createElement('option');
                                option.value = pet.id;
                                option.textContent = `${pet.name} (${pet.species})`;
                                option.dataset.notes = pet.notes || '';
                                petSelect.appendChild(option);
                            });

                            // Ensure notes field is updated if pet is pre-selected
                            if (petSelect.options.length > 1) {
                                petSelect.selectedIndex = 1;
                                petSelect.dispatchEvent(new Event('change'));
                            }
                        })
                        .catch(error => {
                            console.error('Failed to load pets:', error);
                        });
                });
            },
            


            datesSet: function(info) {
                const selected = info.startStr.substring(0, 10);
                const current = '{{ $selectedDate }}';
                if (selected !== current) {
                    const url = new URL(window.location.href);
                    url.searchParams.set('date', selected);
                    window.location.href = url.toString();
                }
            }
        });

        window.calendar.render();

        document.getElementById('editAppointmentForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const appointmentId = document.getElementById('edit_appointment_id').value;

    const payload = {
        pet_id: document.getElementById('edit_pet_id').value,
        service_id: document.getElementById('edit_service_id').value,
        price: document.getElementById('edit_price').value,
        notes: document.getElementById('edit_notes').value,
        status: document.getElementById('edit_status').value,
        appointment_date: document.getElementById('edit_appointment_date').value,
        start_time: document.getElementById('edit_start_time').value,
        staff_id: document.getElementById('edit_staff_id').value,
    };

    fetch(`/appointments/${appointmentId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify(payload),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to save appointment.');
            }
            return response.json();
        })
        .then(() => {
            const appointmentId = document.getElementById('edit_appointment_id').value;

            document.getElementById('editAppointmentModal').classList.add('hidden');
            document.getElementById('calendarWrapper').style.display = 'block';

            const existingEvent = window.calendar.getEventById(appointmentId);
            if (existingEvent) {
                existingEvent.remove();
            }

            window.calendar.refetchEvents();
        })
        .catch(error => {
            console.error('Error saving appointment:', error);
            alert('Failed to save appointment.');
        });

});
        flatpickr("#datepicker", {
            defaultDate: "{{ $selectedDate }}",
            onChange: function(selectedDates, dateStr, instance) {
                const url = new URL(window.location.href);
                url.searchParams.set('date', dateStr);
                window.location.href = url.toString();
            }
        });

    });

    function closeAppointmentModal() {
        const modal = document.getElementById('appointmentModal');
        modal.style.display = 'none';
    }
    </script>

@endpush
@endif

</x-app-layout>