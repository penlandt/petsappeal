<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit User: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">

                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-100">Name</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               class="w-full mt-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-black dark:text-white"
                               style="background-color: #fff; color: #000;">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-100">Email</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               class="w-full mt-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-black dark:text-white"
                               style="background-color: #fff; color: #000;">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-100">Admin Status</label>
                        <select name="is_admin"
                                class="w-full mt-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-black dark:text-white"
                                style="background-color: #fff; color: #000;">
                            <option value="0" {{ !$user->is_admin ? 'selected' : '' }}>No</option>
                            <option value="1" {{ $user->is_admin ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('admin.users') }}"
                       class="mr-4 text-sm text-gray-600 dark:text-gray-300 hover:underline">Cancel</a>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
