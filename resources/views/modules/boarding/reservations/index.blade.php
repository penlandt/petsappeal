<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Boarding Reservations Calendar
        </h2>
        @include('modules.boarding.partials.secondary-menu')
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div id="calendar" style="height: 600px;"></div>
        </div>
    </div>

    <!-- FullCalendar Scheduler CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.11.3/main.min.css" rel="stylesheet" />

    <!-- FullCalendar Scheduler JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.11.3/main.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const userLocationId = {{ auth()->user()->selected_location_id ?? 'null' }};

        const calendarEl = document.getElementById('calendar');

        const resources = [
            @foreach ($boardingUnits as $unit)
            {
                id: '{{ $unit->id }}',
                title: '{{ $unit->name }} ({{ ucfirst($unit->size) }})'
            },
            @endforeach
        ];

        const resourceRowHeight = 50;
        const minHeight = 300;
        const calendarHeight = Math.max(minHeight, resourceRowHeight * resources.length);
        calendarEl.style.height = calendarHeight + 'px';

        const calendar = new FullCalendar.Calendar(calendarEl, {
            schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
            initialView: 'resourceTimelineWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'resourceTimelineDay,resourceTimelineWeek,resourceTimelineMonth'
            },
            resourceAreaHeaderContent: 'Boarding Units',
            resources: resources,
            events: {
                url: '{{ route('boarding.reservations.json') }}',
                method: 'GET',
                failure: function() {
                    alert('There was an error while fetching boarding reservations!');
                }
            },
            editable: false,
            selectable: true,
            nowIndicator: true,
            eventOverlap: false,
            allDaySlot: false,
            slotDuration: { days: 1 },
            slotLabelFormat: [{ weekday: 'short', month: 'numeric', day: 'numeric' }],
            scrollTime: '00:00:00',

            dateClick: function(info) {
                if (!userLocationId) {
                    alert('Please select a location before creating a reservation.');
                    window.location.href = "{{ route('boarding.location.select') }}";
                    return;
                }

                const url = `/boarding/reservations/create?location_id=${encodeURIComponent(userLocationId)}&boarding_unit_id=${encodeURIComponent(info.resource.id)}&checkin_date=${encodeURIComponent(info.dateStr)}`;
                window.location.href = url;
            },

            eventClick: function(info) {
                if (info.event && info.event.id) {
                    const editUrl = `/boarding/reservations/${info.event.id}/edit`;
                    window.location.href = editUrl;
                } else {
                    alert("This reservation is missing an ID and can't be edited.");
                }
            }
        });

        calendar.render();
    });
    </script>
</x-app-layout>
