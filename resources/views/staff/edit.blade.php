<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Edit Staff Member
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            {{-- Main Staff Update Form --}}
            <form method="POST" action="{{ route('staff.update', $staff->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                @php
                    $locationIsMissing = !$locations->contains('id', $staff->location_id);
                    $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    $timeOptions = ['OFF'];
                    for ($hour = 0; $hour < 24; $hour++) {
                        foreach ([0, 15, 30, 45] as $minute) {
                            $formatted = sprintf('%02d:%02d', $hour, $minute);
                            $timeOptions[] = $formatted;
                        }
                    }
                    $availabilityByDay = $staff->availabilities->keyBy('day_of_week');
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if ($locationIsMissing)
                        <div class="mb-2 text-yellow-500 font-semibold col-span-full">
                            ⚠️ This staff member is assigned to an inactive location and cannot be reassigned until you activate that location or select a new one.
                        </div>
                    @endif

                    <div>
                        <label for="location_id" class="block font-medium text-gray-700 dark:text-gray-300">Location</label>
                        <select id="location_id" name="location_id" required
                                style="background-color: #fff; color: #000;"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600">
                            <option value="">-- Select a Location --</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" @if ((int) $location->id === (int) ($staff->location_id ?? -1)) selected @endif>
                                    {{ $location->name }} — {{ $location->city }}, {{ $location->state }} {{ $location->postal_code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Type</label>
                        <select name="type" required style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700">
                            <option value="">-- Select Type --</option>
                            <option value="Employee" {{ old('type', $staff->type) == 'Employee' ? 'selected' : '' }}>Employee</option>
                            <option value="Independent Contractor" {{ old('type', $staff->type) == 'Independent Contractor' ? 'selected' : '' }}>Independent Contractor</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Job Title</label>
                        <input type="text" name="job_title" value="{{ old('job_title', $staff->job_title) }}" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $staff->first_name) }}" required style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $staff->last_name) }}" required style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $staff->phone) }}" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Email</label>
                        <input type="email" name="email" value="{{ old('email', $staff->email) }}" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Address</label>
                        <input type="text" name="address" value="{{ old('address', $staff->address) }}" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">City</label>
                        <input type="text" name="city" value="{{ old('city', $staff->city) }}" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">State</label>
                        <select name="state" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700">
                            <option value="">-- Select State --</option>
                            @foreach ($states as $abbr => $name)
                                <option value="{{ $abbr }}" {{ old('state', $staff->state) == $abbr ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Postal Code</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $staff->postal_code) }}" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $staff->start_date) }}" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $staff->end_date) }}" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Notes</label>
                    <textarea name="notes" rows="4" style="background-color: #fff; color: #000;" class="w-full rounded border-gray-300 dark:border-gray-700">{{ old('notes', $staff->notes) }}</textarea>
                </div>

                <div class="overflow-x-auto mt-8">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Weekly Availability</h3>
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
                @php
                    $startVal = old("availability.$day.start_time", $availabilityByDay[$day]->start_time ?? '08:00');
                    $endVal = old("availability.$day.end_time", $availabilityByDay[$day]->end_time ?? '17:00');
                @endphp
                <tr>
                    <td class="py-2 text-gray-900 dark:text-gray-100">{{ $day }}</td>
                    <td class="py-2">
                        <select name="availability[{{ $day }}][0][start]" class="rounded border-gray-300 dark:border-gray-700" style="background-color: #fff; color: #000;">
                            @foreach ($timeOptions as $time)
                                <option value="{{ $time }}" {{ $startVal === $time ? 'selected' : '' }}>{{ $time }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="py-2">
                        <select name="availability[{{ $day }}][0][end]" class="rounded border-gray-300 dark:border-gray-700" style="background-color: #fff; color: #000;">
                            @foreach ($timeOptions as $time)
                                <option value="{{ $time }}" {{ $endVal === $time ? 'selected' : '' }}>{{ $time }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


                <div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Update Staff Member
                    </button>
                </div>
            </form>

            {{-- Divider --}}
            <div class="my-10 border-t border-gray-300 dark:border-gray-600"></div>

            {{-- Separate Availability Exception Form --}}
<form method="POST" action="{{ route('availability-exceptions.store') }}"
      x-data="{
          startDate: '',
          endDate: '',
          sameDay() {
              return this.startDate && this.endDate && this.startDate === this.endDate;
          }
      }"
      x-init="$watch('startDate', value => { if (!endDate) endDate = value; })"
      class="mt-10 space-y-6 bg-white dark:bg-gray-800 p-6 rounded text-black dark:text-white">
    @csrf
    <input type="hidden" name="staff_id" value="{{ $staff->id }}" />

    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Add Availability Exception</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Start Date</label>
            <input type="date"
                   name="availability_exception[start_date]"
                   x-model="startDate"
                   class="mt-1 block w-full rounded border border-gray-300 dark:border-gray-700"
                   style="background-color: #1f2937; color: #ffffff;">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">End Date</label>
            <input type="date"
                   name="availability_exception[end_date]"
                   x-model="endDate"
                   class="mt-1 block w-full rounded border border-gray-300 dark:border-gray-700"
                   style="background-color: #1f2937; color: #ffffff;">
        </div>

        <div x-show="sameDay()" x-cloak>
            <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">Start Time</label>
            <select name="availability_exception[start_time]"
                    class="w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-black dark:text-white">
                <option value="">-- Select Start Time --</option>
                @for ($h = 0; $h < 24; $h++)
                    @foreach ([0, 15, 30, 45] as $m)
                        @php $t = sprintf('%02d:%02d', $h, $m); @endphp
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                @endfor
            </select>
        </div>

        <div x-show="sameDay()" x-cloak>
            <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">End Time (Optional)</label>
            <select name="availability_exception[end_time]"
                    class="w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-black dark:text-white">
                <option value="">-- Select End Time --</option>
                @for ($h = 0; $h < 24; $h++)
                    @foreach ([0, 15, 30, 45] as $m)
                        @php $t = sprintf('%02d:%02d', $h, $m); @endphp
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                @endfor
            </select>
            @error('availability_exception.end_time')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Add Availability Exception
        </button>
    </div>
</form>
            {{-- Availability Exceptions Table --}}
            @if ($staff->availabilityExceptions->count())
                <div class="mt-10">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Existing Availability Exceptions</h3>
                    <table class="w-full text-left text-sm border dark:border-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="py-2 px-3 text-gray-900 dark:text-gray-100">Start Date</th>
                                <th class="py-2 px-3 text-gray-900 dark:text-gray-100">End Date</th>
                                <th class="py-2 px-3 text-gray-900 dark:text-gray-100">Start Time</th>
                                <th class="py-2 px-3 text-gray-900 dark:text-gray-100">End Time</th>
                                <th class="py-2 px-3 text-gray-900 dark:text-gray-100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($staff->availabilityExceptions as $exception)
                                <tr class="border-t dark:border-gray-700">
                                    <td class="py-2 px-3 text-gray-900 dark:text-gray-100">{{ $exception->start_date }}</td>
                                    <td class="py-2 px-3 text-gray-900 dark:text-gray-100">{{ $exception->end_date }}</td>
                                    <td class="py-2 px-3 text-gray-900 dark:text-gray-100">{{ $exception->start_time ?? '—' }}</td>
                                    <td class="py-2 px-3 text-gray-900 dark:text-gray-100">{{ $exception->end_time ?? '—' }}</td>
                                    <td class="py-2 px-3">
                                        <form method="POST" action="{{ route('availability-exceptions.destroy', $exception->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
