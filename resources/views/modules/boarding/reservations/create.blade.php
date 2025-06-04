<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Create Boarding Reservation
        </h2>
    </x-slot>

    @php
        $boardingUnitId = request()->query('boarding_unit_id', old('boarding_unit_id'));
        $checkinDate = request()->query('checkin_date', old('check_in_date'));
    @endphp

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-sm rounded p-6">
            <form method="POST" action="{{ route('boarding.reservations.store') }}">
                @csrf

                <input type="hidden" id="location_id" name="location_id" value="{{ $locationId }}">

                <div class="mb-4">
    <label for="client_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Client</label>
    <div class="flex gap-2 items-center">
        <select id="client-select" name="client_id" required
                class="mt-1 flex-grow rounded-md shadow-sm"
                style="background-color: #fff; color: #000;">
            <option value="">-- Select Client --</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}"
                    @if(old('client_id') == $client->id) selected @endif>
                    {{ $client->first_name }} {{ $client->last_name }}
                </option>
            @endforeach
        </select>
        <button type="button"
            onclick="document.getElementById('newClientModal').classList.remove('hidden')"
            class="mt-1 bg-green-600 hover:bg-green-700 text-white font-bold px-3 py-1 rounded min-w-[2.5rem]">
            +
        </button>
    </div>
</div>


                <div class="mb-4">
                    <label for="boarding_unit_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Boarding Unit</label>
                    <select id="unit-select" name="boarding_unit_id" required class="mt-1 block w-full rounded-md shadow-sm"
                            style="background-color: #fff; color: #000;">
                        <option value="">-- Select Unit --</option>
                        @foreach ($boardingUnits as $unit)
                            <option value="{{ $unit->id }}" data-max-occupants="{{ $unit->max_occupants }}"
                                @if ($boardingUnitId == $unit->id) selected @endif
                            >
                                {{ $unit->name }} ({{ ucfirst($unit->size) }}, max {{ $unit->max_occupants }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Select Pets</label>
                    <div class="flex items-start gap-2">
                        <div id="pet-selection" class="block w-full rounded-md shadow-sm bg-white text-black dark:bg-gray-700 dark:text-gray-100 p-3"
                            style="min-height: 120px;">
                            <p class="text-gray-500 dark:text-gray-400">Select a client and boarding unit to load pets.</p>
                        </div>
                        <button type="button"
                            onclick="document.getElementById('newPetModal').classList.remove('hidden')"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold px-3 py-1 rounded min-w-[2.5rem] h-[2.5rem]">
                            +
                        </button>
                    </div>
                </div>


                <div class="mb-4">
                    <label for="check_in_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Check-In Date</label>
                    <input id="check_in_date" name="check_in_date" type="date" required
                           class="mt-1 block w-full rounded-md shadow-sm"
                           style="background-color: #fff; color: #000;"
                           value="{{ $checkinDate }}" />
                </div>

                <div class="mb-6">
                    <label for="check_out_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Check-Out Date</label>
                    <input id="check_out_date" name="check_out_date" type="date" required
                           class="mt-1 block w-full rounded-md shadow-sm"
                           style="background-color: #fff; color: #000;"
                           value="{{ old('check_out_date') }}" />
                </div>

                <div class="mb-4">
                    <label for="price_total" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Price</label>
                    <input type="number" name="price_total" id="price_total" step="0.01" min="0"
                        class="mt-1 block w-full rounded-md shadow-sm"
                        style="background-color: #fff; color: #000;"
                        value="{{ old('price_total') }}" required>
                </div>

                <div class="mb-4">
                    <label for="notes" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Notes</label>
                    <textarea id="notes" name="notes"
                            class="mt-1 block w-full rounded-md shadow-sm"
                            style="background-color: #fff; color: #000;"
                            rows="4">{{ old('notes') }}</textarea>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('boarding.reservations.index') }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Cancel
                    </a>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Continue
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.default.min.css" rel="stylesheet" />

<script>

const clientSelect = document.querySelector('#client_id')?.tomselect;

document.addEventListener('DOMContentLoaded', function () {
    const clientSelect = new TomSelect('#client-select', {
        maxItems: 1,
        onInitialize: function () {
            this.wrapper.classList.add('w-full');
        }
    });
    const unitSelect = new TomSelect('#unit-select', { maxItems: 1 });
    const petSelectionDiv = document.getElementById('pet-selection');
    const notesField = document.getElementById('notes');
    const checkInInput = document.getElementById('check_in_date');
    const checkOutInput = document.getElementById('check_out_date');
    const unitDomSelect = document.getElementById('unit-select');
    const priceField = document.getElementById('price_total');
    const locationId = document.getElementById('location_id').value;

    function getMaxOccupants() {
        const selectedOption = unitDomSelect.options[unitDomSelect.selectedIndex];
        return selectedOption ? parseInt(selectedOption.dataset.maxOccupants) || 0 : 0;
    }

    async function loadPets(clientId) {
        if (!clientId) {
            petSelectionDiv.innerHTML = '<p class="text-gray-500 dark:text-gray-400">Select a client and boarding unit to load pets.</p>';
            return;
        }

        try {
            const response = await fetch(`/api/clients/${clientId}/pets`);
            const pets = await response.json();

            if (pets.length === 0) {
                petSelectionDiv.innerHTML = '<p class="text-gray-500 dark:text-gray-400">No active pets found for this client.</p>';
                return;
            }

            renderPetCheckboxes(pets);
        } catch (error) {
            petSelectionDiv.innerHTML = '<p class="text-red-500">Error loading pets.</p>';
        }
    }

    function renderPetCheckboxes(pets) {
        const maxOccupants = getMaxOccupants();

        let html = `<p>Select up to ${maxOccupants} pet(s):</p>`;
        html += '<div class="grid grid-cols-2 gap-2 mt-2">';
        pets.forEach(pet => {
            html += `
                <label class="inline-flex items-center">
                    <input type="checkbox" name="pets[]" value="${pet.id}" class="mr-2 pet-checkbox" />
                    ${pet.name}
                </label>
            `;
        });
        html += '</div>';
        petSelectionDiv.innerHTML = html;

        const checkboxes = petSelectionDiv.querySelectorAll('.pet-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const checked = petSelectionDiv.querySelectorAll('.pet-checkbox:checked');
                if (checked.length > maxOccupants) {
                    checkbox.checked = false;
                    alert(`You can select up to ${maxOccupants} pets.`);
                } else {
                    updateNotes();
                    calculateTotalPrice();
                }
            });
        });

        updateNotes();
        calculateTotalPrice();
    }

    async function updateNotes() {
        const checked = petSelectionDiv.querySelectorAll('.pet-checkbox:checked');
        const petIds = Array.from(checked).map(cb => cb.value);

        if (petIds.length === 0) {
            notesField.value = '';
            return;
        }

        try {
            const response = await fetch("{{ route('boarding.fetch-pet-notes') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ pet_ids: petIds })
            });

            const data = await response.json();
            notesField.value = data.notes;
        } catch (error) {
            console.error('Error fetching notes:', error);
        }
    }

    async function calculateTotalPrice() {
        const checkIn = checkInInput.value;
        const checkOut = checkOutInput.value;
        const unitId = unitDomSelect.value;
        const petCount = petSelectionDiv.querySelectorAll('.pet-checkbox:checked').length;

        if (!checkIn || !checkOut || !unitId || !locationId || petCount === 0) {
            priceField.value = '';
            return;
        }

        const checkInDate = new Date(checkIn);
        const checkOutDate = new Date(checkOut);
        const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));

        if (nights <= 0) {
            priceField.value = '';
            return;
        }

        try {
            const [unitRes, locationRes] = await Promise.all([
                fetch(`/api/boarding-units/${unitId}`),
                fetch(`/api/locations/${locationId}`)
            ]);

            if (!unitRes.ok || !locationRes.ok) {
                priceField.value = '';
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
            console.error("Price calculation failed:", err);
            priceField.value = '';
        }
    }

    clientSelect.on('change', value => {
        loadPets(value);
    });

    unitSelect.on('change', () => {
        const clientId = clientSelect.getValue();
        if (clientId) {
            loadPets(clientId);
        }
    });

    checkOutInput.addEventListener('blur', calculateTotalPrice);

    const preselectedClientId = clientSelect.getValue();
    if (preselectedClientId) {
        loadPets(preselectedClientId);
    }

    // 🔧 Modal form handler
    function handleModalForm(modalId, formId, onSuccess) {
        const modal = document.getElementById(modalId);
        const form = document.getElementById(formId);

        if (!modal || !form) return;

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const clientIdField = form.querySelector('#new_pet_client_id');
            if (clientIdField) {
                clientIdField.value = clientSelect.getValue() || '';
            }

            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                },

                body: formData
            });

            if (response.ok) {
                const data = await response.json();
                modal.classList.add('hidden');
                form.reset();

                if (typeof onSuccess === 'function') {
                    onSuccess(data);
                }
            } else {
                alert('Something went wrong while saving.');
            }
        });
    }

    handleModalForm('newClientModal', 'newClientForm', function(data) {
        const newOption = { value: data.id, text: data.first_name + ' ' + data.last_name };
        clientSelect.addOption(newOption);
        clientSelect.addItem(data.id);
        clientSelect.refreshOptions(false);
        loadPets(data.id);
    });

    handleModalForm('newPetModal', 'newPetForm', function(data) {
        const selectedClientId = clientSelect.getValue();
        if (selectedClientId) {
            loadPets(selectedClientId);
        }
    });
});

// When the "+" button to add a new pet is clicked, populate the client_id in the form
document.querySelectorAll('#newClientModal .cancel-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('newClientModal').classList.add('hidden');
        document.getElementById('newClientModal').classList.remove('flex');
    });
});

// Cancel button for New Pet Modal
document.querySelectorAll('#newPetModal .cancel-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('newPetModal').classList.add('hidden');
        document.getElementById('newPetModal').classList.remove('flex');
    });
});


</script>
@endpush


    @include('partials.modals.new-client')
    @include('partials.modals.new-pet')

</x-app-layout>
