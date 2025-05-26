<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight text-center">
            Edit Client
        </h2>
    </x-slot>

    <div class="py-12 flex justify-center">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow" style="width: 640px; margin-left: auto; margin-right: auto;">
            <form method="POST" action="{{ route('clients.update', $client) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-black dark:text-white font-semibold">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $client->first_name) }}" required
                        class="w-full mt-1 p-2 rounded border border-gray-300"
                        style="background-color: white; color: black;">
                </div>

                <div>
                    <label class="block text-black dark:text-white font-semibold">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $client->last_name) }}" required
                        class="w-full mt-1 p-2 rounded border border-gray-300"
                        style="background-color: white; color: black;">
                </div>

                <div>
                    <label class="block text-black dark:text-white font-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email', $client->email) }}"
                        class="w-full mt-1 p-2 rounded border border-gray-300"
                        style="background-color: white; color: black;">
                </div>

                <div>
                    <label class="block text-black dark:text-white font-semibold">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $client->phone) }}"
                        class="w-full mt-1 p-2 rounded border border-gray-300"
                        style="background-color: white; color: black;">
                </div>

                <div>
                    <label class="block text-black dark:text-white font-semibold">Address</label>
                    <input type="text" name="address" value="{{ old('address', $client->address) }}"
                        class="w-full mt-1 p-2 rounded border border-gray-300"
                        style="background-color: white; color: black;">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-black dark:text-white font-semibold">City</label>
                        <input type="text" name="city" value="{{ old('city', $client->city) }}"
                            class="w-full mt-1 p-2 rounded border border-gray-300"
                            style="background-color: white; color: black;">
                    </div>
                    <div>
                        <label class="block text-black dark:text-white font-semibold">State</label>
                        <select name="state"
                            class="w-full mt-1 p-2 rounded border border-gray-300 appearance-none"
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
                                <option value="{{ $abbr }}" @if(old('state', $client->state) == $abbr) selected @endif>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-black dark:text-white font-semibold">Postal Code</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $client->postal_code) }}"
                            class="w-full mt-1 p-2 rounded border border-gray-300"
                            style="background-color: white; color: black;">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Update Client
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
