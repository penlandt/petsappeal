<x-client-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Add New Pet
        </h2>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto">
        <form method="POST" action="{{ route('client.pets.store') }}">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100">
            </div>

            <!-- Species -->
            <div class="mb-4">
                <label for="species" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Species</label>
                <input type="text" name="species" id="species" value="{{ old('species') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100">
            </div>

            <!-- Breed -->
            <div class="mb-4">
                <label for="breed" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Breed</label>
                <input type="text" name="breed" id="breed" value="{{ old('breed') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100">
            </div>

            <!-- Birthdate -->
            <div class="mb-4">
                <label for="birthdate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Birthdate</label>
                <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100">
            </div>

            <!-- Gender -->
            <div class="mb-4">
                <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gender</label>
                <select name="gender" id="gender"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100">
                    <option value="">Select</option>
                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <!-- Color -->
            <div class="mb-4">
                <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Color</label>
                <input type="text" name="color" id="color" value="{{ old('color') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100">
            </div>

            <!-- Inactive -->
            <div class="mb-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="inactive" class="rounded border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100"
                           {{ old('inactive') ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Mark as Inactive</span>
                </label>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('client.pets.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white mr-4">Cancel</a>
                <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                    Save Pet
                </button>
            </div>
        </form>
    </div>
</x-client-layout>
