<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Edit Email Template
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">

        {{-- Test email status --}}
        @if (session('test_sent'))
            <div class="mb-4 text-green-600 dark:text-green-400">
                {{ session('test_sent') }}
            </div>
        @endif

        {{-- Send Test Email Form --}}
        <div class="flex justify-end mb-6">
            <form method="POST" action="{{ route('settings.email-templates.test', $emailTemplate) }}">
                @csrf
                <button type="submit"
                    class="bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-700 text-sm">
                    Send Test Email
                </button>
            </form>
        </div>

        {{-- Main Edit Form --}}
        <form method="POST" action="{{ route('settings.email-templates.update', $emailTemplate) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
                <input type="text" name="subject" value="{{ old('subject', $emailTemplate->subject) }}"
                    required
                    class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm"
                    style="background-color: #fff; color: #000;">
                @error('subject')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Body (HTML)</label>
                <textarea name="body_html" rows="10"
                    class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm"
                    style="background-color: #fff; color: #000;">{{ old('body_html', $emailTemplate->body_html) }}</textarea>
                @error('body_html')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Body (Plain Text)</label>
                <textarea name="body_plain" rows="6"
                    class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm"
                    style="background-color: #fff; color: #000;">{{ old('body_plain', $emailTemplate->body_plain) }}</textarea>
                @error('body_plain')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex justify-between">
                <a href="{{ route('settings.email-templates.index') }}"
                   class="text-gray-600 hover:underline dark:text-gray-300">Cancel</a>

                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save Template
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
