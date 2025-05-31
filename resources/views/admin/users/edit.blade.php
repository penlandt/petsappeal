<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Manage User Account
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-sm rounded p-6">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                @if(session('success'))
                    <div class="mb-4 text-green-600 dark:text-green-400 font-semibold">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 text-red-600 dark:text-red-400 font-semibold">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-4">
                    <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Name</label>
                    <input id="name" name="name" type="text" required value="{{ old('name', $user->name) }}"
                           class="mt-1 block w-full rounded-md shadow-sm"
                           style="background-color: #fff; color: #000;">
                </div>

                <div class="mb-4">
                    <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email</label>
                    <input id="email" name="email" type="email" required value="{{ old('email', $user->email) }}"
                           class="mt-1 block w-full rounded-md shadow-sm"
                           style="background-color: #fff; color: #000;">
                </div>

                <div class="mb-4">
                    <label for="is_admin" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Is Admin?</label>
                    <select id="is_admin" name="is_admin" required
                            class="mt-1 block w-full rounded-md shadow-sm"
                            style="background-color: #fff; color: #000;">
                        <option value="0" {{ !$user->is_admin ? 'selected' : '' }}>No</option>
                        <option value="1" {{ $user->is_admin ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>

                @php
                    $enabledModules = $user->company?->moduleAccess()->pluck('module')->toArray() ?? [];
                @endphp

                <div class="mb-6">
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Enabled Modules</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(['pos' => 'Point of Sale', 'grooming' => 'Grooming', 'boarding' => 'Boarding', 'daycare' => 'Daycare', 'house' => 'House/Pet Sitting'] as $key => $label)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="modules[]" value="{{ $key }}" {{ in_array($key, $enabledModules) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <x-primary-button class="ml-3">
                        Save
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
