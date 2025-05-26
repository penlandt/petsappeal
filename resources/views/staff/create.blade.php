<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Add Staff Member
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <form method="POST" action="{{ route('staff.store') }}" class="space-y-6">
                @csrf

                <div class="mt-4">
                    <label for="location_id" class="block font-medium text-gray-700 dark:text-gray-300">Location</label>
                    <select id="location_id" name="location_id" required
                        style="background-color: #fff; color: #000;"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600">
                        <option value="">-- Select a Location --</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}"
                                {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }} — {{ $location->city }}, {{ $location->state }} {{ $location->postal_code }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Type</label>
                        <select name="type" required style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700">
                            <option value="">-- Select Type --</option>
                            <option value="Employee" {{ old('type') == 'Employee' ? 'selected' : '' }}>Employee</option>
                            <option value="Independent Contractor" {{ old('type') == 'Independent Contractor' ? 'selected' : '' }}>Independent Contractor</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Job Title</label>
                        <input type="text" name="job_title" value="{{ old('job_title') }}" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">City</label>
                        <input type="text" name="city" value="{{ old('city') }}" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">State</label>
                        <select name="state" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700">
                            <option value="">-- Select State --</option>
                            @foreach ($states as $abbr => $name)
                                <option value="{{ $abbr }}" {{ old('state') == $abbr ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Postal Code</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Notes</label>
                    <textarea name="notes" rows="4" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700">{{ old('notes') }}</textarea>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Weekly Availability</h3>

                    @php
                        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        $timeOptions = ['OFF'];
                        for ($hour = 0; $hour < 24; $hour++) {
                            foreach ([0, 15, 30, 45] as $minute) {
                                $formatted = sprintf('%02d:%02d', $hour, $minute);
                                $timeOptions[] = $formatted;
                            }
                        }
                    @endphp

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr>
                                    <th class="py-2 text-gray-900 dark:text-gray-100">Day</th>
                                    <th class="py-2 text-gray-900 dark:text-gray-100">Start Time</th>
                                    <th class="py-2 text-gray-900 dark:text-gray-100">End Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($daysOfWeek as $day)
                                    <tr>
                                        <td class="py-2 text-gray-900 dark:text-gray-100">{{ $day }}</td>
                                        <td class="py-2">
                                            <select name="availability[{{ $day }}][start_time]"
                                                    class="rounded border-gray-300 dark:border-gray-700"
                                                    style="background-color: #fff; color: #000;"
                                                    x-data
                                                    x-on:change="
                                                        if ($event.target.value === 'OFF') {
                                                            $el.closest('tr').querySelector('[name$=\'[end_time]\']').value = 'OFF';
                                                        }
                                                    ">
                                                @foreach ($timeOptions as $time)
                                                    <option value="{{ $time }}" {{ $time === '08:00' ? 'selected' : '' }}>{{ $time }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="py-2">
                                            <select name="availability[{{ $day }}][end_time]"
                                                    class="rounded border-gray-300 dark:border-gray-700"
                                                    style="background-color: #fff; color: #000;">
                                                @foreach ($timeOptions as $time)
                                                    <option value="{{ $time }}" {{ $time === '17:00' ? 'selected' : '' }}>{{ $time }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Save Staff Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
