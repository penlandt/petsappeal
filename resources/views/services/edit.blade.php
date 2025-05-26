<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Edit Service
        </h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <form method="POST" action="{{ route('services.update', $service->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $service->name) }}"
                        style="background-color: #fff; color: #000; width: 100%; padding: 0.5rem; border-radius: 0.375rem;"
                        class="border dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm sm:text-sm">
                </div>

                <div class="mb-4">
                    <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (minutes)</label>
                    <input id="duration" name="duration" type="number" min="1" value="{{ old('duration', $service->duration) }}"
                        style="background-color: #fff; color: #000; width: 100%; padding: 0.5rem; border-radius: 0.375rem;"
                        class="border dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm sm:text-sm">
                </div>

                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price ($)</label>
                    <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $service->price) }}"
                        style="background-color: #fff; color: #000; width: 100%; padding: 0.5rem; border-radius: 0.375rem;"
                        class="border dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm sm:text-sm">
                </div>

                <div class="mb-6">
                    <label class="inline-flex items-center">
                        <input type="hidden" name="inactive" value="0">
                        <input type="checkbox" name="inactive" value="1" {{ old('inactive', $service->inactive) ? 'checked' : '' }}
                            style="margin-right: 0.5rem;"
                            class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="text-gray-700 dark:text-gray-300">Mark as Inactive</span>
                    </label>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Update Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
