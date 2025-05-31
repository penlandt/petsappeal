<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Boarding Reservation
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-sm rounded p-6">
            <form method="POST" action="{{ route('boarding.reservations.update', $reservation->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="client_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Client</label>
                    <select id="client_id" name="client_id" required class="mt-1 block w-full rounded-md shadow-sm"
                            style="background-color: #fff; color: #000;">
                        <option value="">Select a client</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ $reservation->client_id == $client->id ? 'selected' : '' }}>
                                {{ $client->first_name }} {{ $client->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="boarding_unit_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Boarding Unit</label>
                    <select id="boarding_unit_id" name="boarding_unit_id" required class="mt-1 block w-full rounded-md shadow-sm"
                            style="background-color: #fff; color: #000;">
                        <option value="">Select a unit</option>
                        @foreach ($boardingUnits as $unit)
                            <option value="{{ $unit->id }}" data-price="{{ $unit->price_per_night }}"
                                {{ $reservation->boarding_unit_id == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }} ({{ ucfirst($unit->size) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="check_in_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Check-In Date</label>
                    <input type="date" id="check_in_date" name="check_in_date" value="{{ $reservation->check_in_date->format('Y-m-d') }}"
                           required class="mt-1 block w-full rounded-md shadow-sm" style="background-color: #fff; color: #000;">
                </div>

                <div class="mb-4">
                    <label for="check_out_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Check-Out Date</label>
                    <input type="date" id="check_out_date" name="check_out_date" value="{{ $reservation->check_out_date->format('Y-m-d') }}"
                           required class="mt-1 block w-full rounded-md shadow-sm" style="background-color: #fff; color: #000;">
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Pets</label>
                    <div id="pet-checkboxes" class="space-y-2 ml-2"></div>
                </div>

                <div class="mb-4">
                    <label for="price_total" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Total Price</label>
                    <div class="relative">
                        <input type="text" id="price_total" name="price_total" value="{{ $reservation->price_total }}"
                               required readonly class="mt-1 block w-full rounded-md shadow-sm pr-10"
                               style="background-color: #fff; color: #000;">
                        <div id="price-loading-spinner" class="absolute inset-y-0 right-0 flex items-center pr-3 hidden">
                            <svg class="animate-spin h-5 w-5 text-gray-600 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Notes</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="mt-1 block w-full rounded-md shadow-sm" style="background-color: #fff; color: #000;">{{ $reservation->notes }}</textarea>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <a href="{{ route('boarding.reservations.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                        Cancel
                    </a>

                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Update Reservation
                    </button>
                </div>
            </form>

            <form method="POST" action="{{ route('boarding.reservations.destroy', $reservation->id) }}"
                  class="mt-4 text-right"
                  onsubmit="return confirm('Are you sure you want to delete this reservation?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const clientSelect = document.getElementById('client_id');
            const petSelectionDiv = document.getElementById('pet-checkboxes');
            const unitDomSelect = document.getElementById('boarding_unit_id');
            const checkInInput = document.getElementById('check_in_date');
            const checkOutInput = document.getElementById('check_out_date');
            const priceField = document.getElementById('price_total');
            const notesTextarea = document.getElementById('notes');
            const spinner = document.getElementById('price-loading-spinner');

            const locationId = {{ $reservation->location_id }};
            const selectedPetIds = @json($petIds);

            function fetchPets(clientId) {
                fetch(`/api/clients/${clientId}/pets`)
                    .then(response => response.json())
                    .then(data => {
                        petSelectionDiv.innerHTML = '';
                        data.forEach(pet => {
                            const label = document.createElement('label');
                            label.className = 'flex items-center space-x-2 text-sm text-gray-800 dark:text-gray-200';

                            const checkbox = document.createElement('input');
                            checkbox.type = 'checkbox';
                            checkbox.name = 'pets[]';
                            checkbox.value = pet.id;
                            checkbox.classList.add('pet-checkbox', 'rounded');
                            if (selectedPetIds.includes(pet.id)) {
                                checkbox.checked = true;
                            }

                            checkbox.addEventListener('change', () => {
                                fetchPetNotes();
                                calculateTotalPrice();
                            });

                            label.appendChild(checkbox);
                            label.appendChild(document.createTextNode(pet.name));
                            petSelectionDiv.appendChild(label);
                        });

                        fetchPetNotes();
                        calculateTotalPrice();
                    });
            }

            function fetchPetNotes() {
                const selectedPets = Array.from(petSelectionDiv.querySelectorAll('input[name="pets[]"]:checked'))
                    .map(cb => cb.value);

                fetch(`/boarding/reservations/get-pet-notes`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ pet_ids: selectedPets })
                })
                    .then(response => response.json())
                    .then(data => {
                        notesTextarea.value = data.notes;
                    });
            }

            async function calculateTotalPrice() {
                spinner.classList.remove('hidden');

                const checkIn = checkInInput.value;
                const checkOut = checkOutInput.value;
                const unitId = unitDomSelect.value;
                const petCount = petSelectionDiv.querySelectorAll('.pet-checkbox:checked').length;

                if (!checkIn || !checkOut || !unitId || !locationId || petCount === 0) {
                    priceField.value = '';
                    spinner.classList.add('hidden');
                    return;
                }

                const checkInDate = new Date(checkIn);
                const checkOutDate = new Date(checkOut);
                const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));

                if (nights <= 0) {
                    priceField.value = '';
                    spinner.classList.add('hidden');
                    return;
                }

                try {
                    const [unitRes, locationRes] = await Promise.all([
                        fetch(`/api/boarding-units/${unitId}`),
                        fetch(`/api/locations/${locationId}`)
                    ]);

                    if (!unitRes.ok || !locationRes.ok) {
                        priceField.value = '';
                        spinner.classList.add('hidden');
                        return;
                    }

                    const unitData = await unitRes.json();
                    const locationData = await locationRes.json();

                    const unitPrice = parseFloat(unitData.price_per_night || 0);
                    const addlPct = parseFloat(locationData.boarding_chg_per_addl_occpt || 0);

                    const basePrice = unitPrice * nights;
                    const extraCharge = petCount > 1
                        ? unitPrice * (addlPct / 100) * (petCount - 1) * nights
                        : 0;

                    const total = basePrice + extraCharge;
                    priceField.value = total.toFixed(2);
                } catch (err) {
                    priceField.value = '';
                } finally {
                    spinner.classList.add('hidden');
                }
            }

            clientSelect.addEventListener('change', () => {
                fetchPets(clientSelect.value);
            });

            unitDomSelect.addEventListener('change', calculateTotalPrice);
            checkInInput.addEventListener('change', calculateTotalPrice);
            checkOutInput.addEventListener('change', calculateTotalPrice);

            fetchPets(clientSelect.value);
        });
    </script>
</x-app-layout>
