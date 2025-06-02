<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Add New Pet
        </h2>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow p-6 rounded-lg">
            <form method="POST" action="{{ route('pets.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="client_id" class="block text-sm font-medium mb-1 text-gray-900 dark:text-gray-100">Select Owner</label>
                    <select id="client_id" name="client_id"
                            required
                            style="background-color: #fff; color: #000;"
                            class="w-full border rounded px-3 py-1.5">
                        <option value="">-- Select Owner --</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">
                                {{ $client->first_name }} {{ $client->last_name }} ({{ $client->phone }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-gray-900 dark:text-gray-100">Name</label>
                    <input type="text" id="name" name="name"
                           required
                           style="background-color: #fff; color: #000;"
                           class="w-full mt-1 rounded border-gray-300 px-3 py-1.5" />
                </div>

                <div class="mb-4">
                    <label for="species" class="block text-gray-900 dark:text-gray-100">Species</label>
                    <input type="text" id="species" name="species"
                           style="background-color: #fff; color: #000;"
                           class="w-full mt-1 rounded border-gray-300 px-3 py-1.5" />
                </div>

                <div class="mb-4">
                    <label for="breed" class="block text-gray-900 dark:text-gray-100">Breed</label>
                    <input type="text" id="breed" name="breed"
                           style="background-color: #fff; color: #000;"
                           class="w-full mt-1 rounded border-gray-300 px-3 py-1.5" />
                </div>

                <div class="mb-4">
                    <label for="birthdate" class="block text-gray-900 dark:text-gray-100">Birthdate</label>
                    <input type="date" id="birthdate" name="birthdate"
                           style="background-color: #fff; color: #000;"
                           class="w-full mt-1 rounded border-gray-300 px-3 py-1.5" />
                </div>

                <div class="mb-4">
                    <label for="color" class="block text-gray-900 dark:text-gray-100">Color</label>
                    <input type="text" id="color" name="color"
                           style="background-color: #fff; color: #000;"
                           class="w-full mt-1 rounded border-gray-300 px-3 py-1.5" />
                </div>

                <div class="mb-4">
                    <label for="gender" class="block text-gray-900 dark:text-gray-100">Gender</label>
                    <input type="text" id="gender" name="gender"
                           style="background-color: #fff; color: #000;"
                           class="w-full mt-1 rounded border-gray-300 px-3 py-1.5" />
                </div>

                <div class="mb-4">
                    <label for="notes" class="block text-gray-900 dark:text-gray-100">Notes</label>
                    <textarea id="notes" name="notes" rows="3"
                              style="background-color: #fff; color: #000;"
                              class="w-full mt-1 rounded border-gray-300 px-3 py-1.5"></textarea>
                </div>

                <div class="mb-4">
                    <label class="inline-flex items-center text-gray-900 dark:text-gray-100">
                        <input type="checkbox" name="inactive"
                               style="background-color: #fff; color: #000;"
                               class="rounded border-gray-300 px-2 py-2" />
                        <span class="ml-2">Mark as Inactive</span>
                    </label>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('pets.index') }}"
                       class="px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">
                        Cancel
                    </a>

                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Save Pet
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet" />

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new TomSelect('#client_id', {
                create: false,
                allowEmptyOption: true,
                maxOptions: 500,
                placeholder: 'Select a client...',
            });
        });
    </script>
</x-app-layout>
