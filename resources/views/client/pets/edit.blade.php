@extends('layouts.client-layout')

@section('title', 'Edit Pet')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit {{ $pet->name }}</h1>

    <form method="POST" action="{{ route('client.pets.update', $pet) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $pet->name) }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100">
        </div>

        <!-- Species -->
        <div>
            <label for="species" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Species</label>
            <input type="text" name="species" id="species" value="{{ old('species', $pet->species) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100">
        </div>

        <!-- Breed -->
        <div>
            <label for="breed" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Breed</label>
            <input type="text" name="breed" id="breed" value="{{ old('breed', $pet->breed) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100">
        </div>

        <!-- Birthdate -->
        <div>
            <label for="birthdate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Birthdate</label>
            <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate', \Illuminate\Support\Carbon::parse($pet->birthdate)->format('Y-m-d')) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100">
        </div>

        <!-- Gender -->
        <div>
            <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gender</label>
            <select name="gender" id="gender"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100">
                <option value="">-- Select Gender --</option>
                <option value="Male" {{ old('gender', $pet->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender', $pet->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Unknown" {{ old('gender', $pet->gender) === 'Unknown' ? 'selected' : '' }}>Unknown</option>
            </select>
        </div>

        <!-- Color -->
        <div>
            <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Color</label>
            <input type="text" name="color" id="color" value="{{ old('color', $pet->color) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100">
        </div>

        <!-- Inactive -->
        <div class="flex items-center">
            <input type="checkbox" name="inactive" id="inactive" value="1"
                   {{ old('inactive', $pet->inactive) ? 'checked' : '' }}
                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 dark:bg-gray-800">
            <label for="inactive" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Mark as Inactive</label>
        </div>

        <!-- Submit -->
        <div class="pt-4">
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                Save Changes
            </button>
            <a href="{{ route('client.pets.index') }}"
               class="ml-4 text-sm text-gray-600 dark:text-gray-300 hover:underline">
                Cancel
            </a>
        </div>
    </form>
@endsection
