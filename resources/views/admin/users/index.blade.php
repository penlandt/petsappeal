<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Manage User Accounts
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">

            <!-- Filter Box -->
            <form method="GET" action="{{ route('admin.users') }}" class="mb-4">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Search by name or email..."
                       class="w-full sm:w-1/2 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-black dark:text-white bg-white dark:bg-gray-900"
                       style="background-color: #fff; color: #000;">
            </form>

            <!-- User List Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-gray-900 dark:text-gray-100">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-4 py-2">{{ $user->name }}</td>
                                <td class="px-4 py-2">{{ $user->email }}</td>
                                <td class="px-4 py-2 text-right whitespace-nowrap space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:underline">Edit</a>

                                <form method="POST"
                                    action="{{ route('admin.users.destroy', $user) }}"
                                    class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">
                                        Delete
                                    </button>
                                </form>

                                    <a href="{{ route('admin.users.impersonate', $user) }}" class="text-green-600 hover:underline">View As</a>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
