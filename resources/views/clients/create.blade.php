<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight text-center">
            Create Client
        </h2>
    </x-slot>

    <div class="py-12 flex justify-center">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow" style="width: 640px; margin-left: auto; margin-right: auto;">
            <form method="POST" action="{{ route('clients.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-black dark:text-white font-semibold">First Name</label>
                    <input type="text" name="first_name" required
                        class="w-full mt-1 p-2 rounded border border-gray-300 bg-white text-black dark:bg-gray-700 dark:text-white"
                        style="background-color: white; color: black;">
                </div>

                <div>
                    <label class="block text-black dark:text-white font-semibold">Last Name</label>
                    <input type="text" name="last_name" required
                        class="w-full mt-1 p-2 rounded border border-gray-300 bg-white text-black dark:bg-gray-700 dark:text-white"
                        style="background-color: white; color: black;">
                </div>

                <div>
                    <label class="block text-black dark:text-white font-semibold">Email</label>
                    <input type="email" name="email"
                        class="w-full mt-1 p-2 rounded border border-gray-300 bg-white text-black dark:bg-gray-700 dark:text-white"
                        style="background-color: white; color: black;">
                </div>

                <div>
                    <label class="block text-black dark:text-white font-semibold">Phone</label>
                    <input type="text" name="phone"
                        class="w-full mt-1 p-2 rounded border border-gray-300 bg-white text-black dark:bg-gray-700 dark:text-white"
                        style="background-color: white; color: black;">
                </div>

                <div>
                    <label class="block text-black dark:text-white font-semibold">Address</label>
                    <input type="text" name="address"
                        class="w-full mt-1 p-2 rounded border border-gray-300 bg-white text-black dark:bg-gray-700 dark:text-white"
                        style="background-color: white; color: black;">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-black dark:text-white font-semibold">City</label>
                        <input type="text" name="city"
                            class="w-full mt-1 p-2 rounded border border-gray-300 bg-white text-black dark:bg-gray-700 dark:text-white"
                            style="background-color: white; color: black;">
                    </div>
                    <div>
                        <label class="block text-black dark:text-white font-semibold">State</label>
                        <select name="state" required
                            class="w-full mt-1 p-2 rounded border border-gray-300 bg-white text-black dark:bg-gray-700 dark:text-white dark:focus:bg-gray-700 dark:focus:text-white appearance-none"
                            style="background-color: white; color: black;">
                            <option value="">Select a state</option>
                            @foreach([
                                'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California',
                                'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'FL' => 'Florida', 'GA' => 'Georgia',
                                'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa',
                                'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
                                'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri',
                                'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey',
                                'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio',
                                'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
                                'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont',
                                'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming'
                            ] as $abbr => $name)
                                <option value="{{ $abbr }}" @if(old('state') == $abbr) selected @endif>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-black dark:text-white font-semibold">Postal Code</label>
                        <input type="text" name="postal_code"
                            class="w-full mt-1 p-2 rounded border border-gray-300 bg-white text-black dark:bg-gray-700 dark:text-white"
                            style="background-color: white; color: black;">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Save Client
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
