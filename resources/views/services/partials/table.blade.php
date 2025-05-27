<table class="min-w-full text-sm text-gray-900 dark:text-white">
    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white">
        <tr class="border-b border-gray-300 dark:border-gray-700">
            <th class="px-0 py-0 text-left font-medium">Name</th>
            <th class="px-0 py-0 text-left font-medium">Duration</th>
            <th class="px-0 py-0 text-left font-medium">Price</th>
            <th class="px-0 py-0 text-left font-medium">Inactive</th>
            <th class="px-0 py-0 text-left font-medium text-right">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($services as $service)
            <tr class="border-b border-gray-200 dark:border-gray-600">
                <td class="px-0 py-0">{{ $service->name }}</td>
                <td class="px-0 py-0">{{ $service->duration }} mins</td>
                <td class="px-0 py-0">${{ number_format($service->price, 2) }}</td>
                <td class="px-0 py-0">
                    <span class="{{ $service->inactive ? 'text-red-600' : 'text-green-600' }} font-semibold">
                        {{ $service->inactive ? 'Yes' : 'No' }}
                    </span>
                </td>
                <td class="px-0 py-0 text-right">
                    <a href="{{ route('services.edit', $service) }}"
                       class="text-sm text-blue-600 hover:underline">Edit</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
