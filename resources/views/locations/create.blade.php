<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Add New Location
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow p-6 rounded-lg">
            <form method="POST" action="{{ route('locations.store') }}">
                @csrf

@if ($errors->any())
    <div class="mb-4 text-red-600 dark:text-red-400">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-900 dark:text-gray-100">Location Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-white text-black rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>

                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-900 dark:text-gray-100">Street Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-white text-black rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-900 dark:text-gray-100">City</label>
                        <input type="text" name="city" id="city" value="{{ old('city') }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-white text-black rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-900 dark:text-gray-100">State</label>
                        <select name="state" id="state"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-white text-black rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="" disabled {{ old('state') ? '' : 'selected' }}>Select a state</option>
                            @foreach ([
                                'AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado',
                                'CT'=>'Connecticut','DE'=>'Delaware','DC'=>'District of Columbia','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho',
                                'IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana',
                                'ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi',
                                'MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey',
                                'NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma',
                                'OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota',
                                'TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington',
                                'WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming'
                            ] as $abbr => $name)
                                <option value="{{ $abbr }}" {{ old('state') === $abbr ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-900 dark:text-gray-100">Postal Code</label>
                        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-white text-black rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Time Zone</label>
                    <select id="timezone" name="timezone" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md shadow-sm">
                        @foreach(timezone_identifiers_list() as $tz)
                            <option value="{{ $tz }}" {{ old('timezone') === $tz ? 'selected' : '' }}>
                                {{ $tz }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-900 dark:text-gray-100">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-white text-black rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-900 dark:text-gray-100">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-white text-black rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="mb-6 flex items-center">
                    <input type="checkbox" name="inactive" id="inactive"
                        class="mr-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 focus:ring-indigo-500">
                    <label for="inactive" class="text-sm text-gray-900 dark:text-gray-100">Mark as Inactive</label>
                </div>

                <div>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Create Location
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
