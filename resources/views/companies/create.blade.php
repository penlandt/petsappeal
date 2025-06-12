<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Step 1 of 4: Create Your Company
        </h2>
    </x-slot>

    <div class="py-10 px-4 max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
            <form method="POST" action="{{ route('companies.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-200">Company Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full mt-1 rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                </div>

                <!-- Slug -->
                <div class="mt-4">
                    <label for="slug" class="block font-medium text-sm text-gray-700 dark:text-gray-200">Company Slug</label>
                    <input id="slug" name="slug" type="text" value="{{ old('slug') }}" required autofocus
                        class="block mt-1 w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    @error('slug')
                        <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-200">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full mt-1 rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>
                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-200">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            class="w-full mt-1 rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>
                </div>

                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-200">Website</label>
                    <input type="text" name="website" value="{{ old('website') }}"
                        class="w-full mt-1 rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                </div>

                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-200">Notes</label>
                    <textarea name="notes" rows="4"
                        class="w-full mt-1 rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('notes') }}</textarea>
                </div>

                <div class="pt-6 text-right">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Save & Continue
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
