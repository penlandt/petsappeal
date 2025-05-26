<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Client Details
        </h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Client Information</h3>
            <p class="text-gray-900 dark:text-gray-100">
                <strong>Name:</strong>
                <a href="{{ route('clients.edit', $client) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                    {{ $client->first_name }} {{ $client->last_name }}
                </a>
            </p>
            <p class="text-gray-900 dark:text-gray-100"><strong>Email:</strong> {{ $client->email }}</p>
            <p class="text-gray-900 dark:text-gray-100"><strong>Phone:</strong> {{ $client->phone }}</p>

            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mt-6 mb-2">Pets</h3>
            <a href="{{ route('pets.create', ['client_id' => $client->id]) }}"
                class="inline-block mb-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                + Add Pet
            </a>
            @if ($showAll)
                <a href="{{ route('clients.show', $client->id) }}"
                   class="inline-block mb-4 text-blue-600 dark:text-blue-400 hover:underline">
                    Hide Inactive
                </a>
            @else
                <a href="{{ route('clients.show', ['client' => $client->id, 'show' => 'all']) }}"
                   class="inline-block mb-4 text-blue-600 dark:text-blue-400 hover:underline">
                    Show Inactive
                </a>
            @endif

            @if ($client->pets->isEmpty())
                <p class="text-gray-900 dark:text-gray-100">No pets found for this client.</p>
            @else
                <ul class="list-disc list-inside text-gray-900 dark:text-gray-100">
                    @foreach ($client->pets as $pet)
                        <li>
                            <strong class="text-gray-900 dark:text-gray-100">
                                {{ $pet->name }}
                            </strong>
                            ({{ $pet->species ?? 'Unknown Species' }},
                            {{ $pet->breed ?? 'Unknown Breed' }})

                            <a href="{{ route('pets.edit', $pet) }}"
                               class="ml-2 text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                  Edit
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
