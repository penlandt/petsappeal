<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Edit Location
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <form method="POST" action="{{ route('locations.update', $location->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Location Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $location->name) }}"
                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring focus:ring-indigo-200 focus:border-indigo-500 sm:text-sm" />
                </div>

                <div class="mb-4">
                    <label for="address" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Address</label>
                    <input id="address" name="address" type="text" value="{{ old('address', $location->address) }}"
                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring focus:ring-indigo-200 focus:border-indigo-500 sm:text-sm" />
                </div>

                <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="city" class="block font-medium text-sm text-gray-700 dark:text-gray-300">City</label>
                        <input id="city" name="city" type="text" value="{{ old('city', $location->city) }}"
                            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring focus:ring-indigo-200 focus:border-indigo-500 sm:text-sm" />
                    </div>
                    <div>
                        <label for="state" class="block font-medium text-sm text-gray-700 dark:text-gray-300">State</label>
                        <select id="state" name="state"
                            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring focus:ring-indigo-200 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select a state</option>
                            @foreach (config('app.us_states') as $abbr => $state)
                                <option value="{{ $abbr }}" {{ old('state', $location->state) === $abbr ? 'selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="postal_code" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Postal Code</label>
                        <input id="postal_code" name="postal_code" type="text" value="{{ old('postal_code', $location->postal_code) }}"
                            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring focus:ring-indigo-200 focus:border-indigo-500 sm:text-sm" />
                    </div>
                </div>

                <div class="mb-4">
                    <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Time Zone</label>
                    <select id="timezone" name="timezone" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md shadow-sm">
                        @foreach(timezone_identifiers_list() as $tz)
                            <option value="{{ $tz }}" {{ old('timezone', $location->timezone) === $tz ? 'selected' : '' }}>
                                {{ $tz }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="phone" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Phone</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $location->phone) }}"
                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring focus:ring-indigo-200 focus:border-indigo-500 sm:text-sm" />
                </div>

                <div class="mb-4">
                    <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $location->email) }}"
                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring focus:ring-indigo-200 focus:border-indigo-500 sm:text-sm" />
                </div>

                <div class="flex flex-col md:flex-row md:space-x-4">
                    <div class="w-full md:w-1/2">
                        <label for="product_tax_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Tax Rate (%)</label>
                        <input
                            type="number"
                            step="0.01"
                            name="product_tax_rate"
                            id="product_tax_rate"
                            class="form-control bg-white text-black dark:bg-gray-800 dark:text-white"
                            value="{{ old('product_tax_rate', $location->product_tax_rate ?? '') }}"
                        >
                    </div>
                    <div class="w-full md:w-1/2 mt-4 md:mt-0">
                        <label for="service_tax_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Tax Rate (%)</label>
                        <input
                            type="number"
                            step="0.01"
                            name="service_tax_rate"
                            id="service_tax_rate"
                            class="form-control bg-white text-black dark:bg-gray-800 dark:text-white"
                            value="{{ old('service_tax_rate', $location->service_tax_rate ?? '') }}"
                        >
                    </div>
                </div>

                <div class="mb-4">
                    <div class="flex flex-col sm:flex-row sm:space-x-4">
                        <div class="w-full sm:w-1/2">
                            <label for="boarding_check_in_time" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Boarding Check-In Time</label>
                            <input type="time" name="boarding_check_in_time" id="boarding_check_in_time"
                                class="mt-1 block w-full rounded-md shadow-sm bg-white text-black dark:bg-gray-800 dark:text-white"
                                value="{{ old('boarding_check_in_time', $location->boarding_check_in_time) }}">
                        </div>

                        <div class="w-full sm:w-1/2 mt-4 sm:mt-0">
                            <label for="boarding_check_out_time" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Boarding Check-Out Time</label>
                            <input type="time" name="boarding_check_out_time" id="boarding_check_out_time"
                                class="mt-1 block w-full rounded-md shadow-sm bg-white text-black dark:bg-gray-800 dark:text-white"
                                value="{{ old('boarding_check_out_time', $location->boarding_check_out_time) }}">
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="boarding_chg_per_addl_occpt" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                        Boarding Charge per Add’l Occupant (%)
                        <span class="ml-1 text-gray-400 cursor-pointer" title="By default no extra charge is assessed for more than one occupant of a boarding unit. If you would like to charge more per additional occupant, please enter the percentage of the full boarding charge here.">
                            &#9432;
                        </span>
                    </label>
                    <input type="number" step="0.01" min="0" max="100" name="boarding_chg_per_addl_occpt" id="boarding_chg_per_addl_occpt"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-white text-black dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ old('boarding_chg_per_addl_occpt', isset($location) ? $location->boarding_chg_per_addl_occpt : '') }}">
                </div>






                <div class="mb-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="inactive" value="1"
                               {{ old('inactive', $location->inactive) ? 'checked' : '' }}
                               class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Mark as Inactive</span>
                    </label>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Update Location
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
