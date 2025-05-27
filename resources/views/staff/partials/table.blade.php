<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100">
        <tr>
            <th class="text-left font-semibold px-2 py-1">Name</th>
            <th class="text-left font-semibold px-2 py-1">Job Title</th>
            <th class="text-left font-semibold px-2 py-1">Type</th>
            <th class="text-left font-semibold px-2 py-1">Location</th>
            <th class="text-left font-semibold px-2 py-1">Phone</th>
            <th class="text-left font-semibold px-2 py-1">Email</th>
            <th class="text-left font-semibold px-2 py-1">Start Date</th>
            <th class="text-left font-semibold px-2 py-1">End Date</th>
            <th class="text-left font-semibold px-2 py-1">Actions</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-900 dark:text-gray-100">
        @forelse ($staff as $member)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-2 py-0.5">{{ $member->first_name }} {{ $member->last_name }}</td>
                <td class="px-2 py-0.5">{{ $member->job_title }}</td>
                <td class="px-2 py-0.5">{{ $member->type }}</td>
                <td class="px-2 py-0.5">
                    {{ optional($member->location)->name }}
                    @if($member->location)
                        ({{ $member->location->city }}, {{ $member->location->state }}, {{ $member->location->postal_code }})
                    @endif
                </td>
                <td class="px-2 py-0.5">{{ $member->phone }}</td>
                <td class="px-2 py-0.5">{{ $member->email }}</td>
                <td class="px-2 py-0.5">{{ $member->start_date }}</td>
                <td class="px-2 py-0.5">{{ $member->end_date }}</td>
                <td class="px-2 py-0.5">
                    <a href="{{ route('staff.edit', $member->id) }}"
                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        Edit
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center text-gray-500 dark:text-gray-400 py-2">No staff found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
