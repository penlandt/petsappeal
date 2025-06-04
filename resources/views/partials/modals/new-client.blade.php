<!-- New Client Modal -->
<div id="newClientModal"
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
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Add New Client</h3>
        <form id="newClientForm" action="{{ route('clients.store') }}">
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

            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Street Address</label>
                <input type="text" id="address" name="address" class="w-full border border-gray-300 rounded p-2" style="background-color: #fff; color: #000;">
            </div>

            <div class="mb-4">
                <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                <input type="text" id="city" name="city" class="w-full border border-gray-300 rounded p-2" style="background-color: #fff; color: #000;">
            </div>

            <div class="mb-4">
                <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">State</label>
                <select id="state" name="state" class="w-full border border-gray-300 rounded p-2" style="background-color: #fff; color: #000;">
                    <option value="">-- Select a state --</option>
                    @foreach ([
                        'AL','AK','AZ','AR','CA','CO','CT','DC','DE','FL','GA','HI','ID','IL','IN','IA','KS','KY','LA',
                        'ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','OH','OK',
                        'OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY','DC'
                    ] as $abbr)
                        <option value="{{ $abbr }}">{{ $abbr }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Postal Code</label>
                <input type="text" id="postal_code" name="postal_code" class="w-full border border-gray-300 rounded p-2" style="background-color: #fff; color: #000;">
            </div>

            <div class="flex justify-end">
                <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded modal-cancel-button">
                    Cancel
                </button>

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Client</button>
            </div>
        </form>
    </div>
</div>