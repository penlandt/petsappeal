<!-- Pet Modal -->
<div id="newPetModal"
     class="hidden fixed z-[9999] bg-black bg-opacity-60"
     style="
         top: 50%;
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
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Add New Pet</h3>
        <form id="newPetForm" method="POST" action="{{ route('pets.store') }}">
    @csrf
    <input type="hidden" name="client_id" id="new_pet_client_id" />

            <div class="mb-4">
                <label for="pet_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pet Name</label>
                <input type="text" id="pet_name" name="name" class="w-full border border-gray-300 rounded p-2"
                       style="background-color: #fff; color: #000;">
            </div>

            <div class="mb-4">
                <label for="species" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Species</label>
                <input type="text" id="species" name="species" class="w-full border border-gray-300 rounded p-2"
                       style="background-color: #fff; color: #000;">
            </div>

            <div class="mb-4">
                <label for="breed" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Breed</label>
                <input type="text" id="breed" name="breed" class="w-full border border-gray-300 rounded p-2"
                       style="background-color: #fff; color: #000;">
            </div>

            <div class="mb-4">
                <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Color</label>
                <input type="text" id="color" name="color" class="w-full border border-gray-300 rounded p-2"
                       style="background-color: #fff; color: #000;">
            </div>

            <div class="mb-4">
    <label for="sex" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sex</label>
    <select id="sex" name="sex" class="w-full border border-gray-300 rounded p-2"
            style="background-color: #fff; color: #000;" required>
        <option value="">-- Select sex --</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Unknown">Unknown</option>
    </select>
</div>

<div class="mb-4">
    <label for="weight" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Weight (lbs)</label>
    <input type="number" step="0.01" id="weight" name="weight" class="w-full border border-gray-300 rounded p-2"
           style="background-color: #fff; color: #000;">
</div>


            <div class="mb-4">
                <label for="birthday" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Birthday</label>
                <input type="date" id="birthday" name="birthday" class="w-full border border-gray-300 rounded p-2"
                       style="background-color: #fff; color: #000;">
            </div>

            <div class="mb-4">
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                <textarea id="notes" name="notes" rows="3" class="w-full border border-gray-300 rounded p-2"
                          style="background-color: #fff; color: #000;"></textarea>
            </div>

            <div class="flex justify-end">
                <button type="button" class="cancel-btn bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded"
                        data-close="newPetModal">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Save Pet
                </button>
            </div>
        </form>
    </div>
</div>