<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Company Info
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            @if (session('success'))
                <div class="mb-4 text-green-600 dark:text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('companies.update', $companies->first()->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block font-semibold text-gray-900 dark:text-gray-100">Company Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $companies->first()->name) }}"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
                </div>

                <div class="mb-4">
                    <label for="slug" class="block font-semibold text-gray-900 dark:text-gray-100">Company Slug</label>
                    <input id="slug" name="slug" type="text" value="{{ old('slug', $companies->first()->slug) }}"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        placeholder="e.g., acme-grooming" required>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        This will be used in your client portal URL: <code>https://pets-appeal.com/book/<span class="italic">your-slug</span></code>
                    </p>
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block font-semibold text-gray-900 dark:text-gray-100">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $companies->first()->email) }}"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>

                <div class="mb-4">
                    <label for="phone" class="block font-semibold text-gray-900 dark:text-gray-100">Phone</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $companies->first()->phone) }}"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>

                <div class="mb-4">
                    <label for="website" class="block font-semibold text-gray-900 dark:text-gray-100">Website</label>
                    <input id="website" name="website" type="text" value="{{ old('website', $companies->first()->website) }}"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>

                <div class="mb-4">
                    <label for="notes" class="block font-semibold text-gray-900 dark:text-gray-100">Notes</label>
                    <textarea id="notes" name="notes"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        rows="4">{{ old('notes', $companies->first()->notes) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="logo" class="block font-semibold text-gray-900 dark:text-gray-100">Company Logo</label>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        Recommended size: 300×300 pixels. Max file size: 1MB.
                    </p>
                    <input id="logo" name="logo" type="file" accept="image/*"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">

                    @error('logo')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    @if ($companies->first()->logo_path)
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Current Logo:</p>
                            <img src="{{ asset('storage/' . $companies->first()->logo_path) }}" alt="Company Logo" class="h-20 rounded shadow">
                        </div>
                    @endif
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
