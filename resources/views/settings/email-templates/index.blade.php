<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Email Templates
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 text-green-600 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border dark:border-gray-700">
                <thead class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">Type</th>
                        <th class="px-4 py-2 text-left">Key</th>
                        <th class="px-4 py-2 text-left">Subject</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($templates as $template)
                        <tr class="border-t dark:border-gray-700">
                            <td class="px-4 py-2">{{ ucfirst($template->type) }}</td>
                            <td class="px-4 py-2">{{ $template->template_key }}</td>
                            <td class="px-4 py-2">{{ $template->subject }}</td>
                            <td class="px-4 py-2 text-right">
                                <a href="{{ route('settings.email-templates.edit', $template) }}"
                                   class="text-blue-600 hover:underline dark:text-blue-400">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
