@extends('layouts.guest')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Contact Us</h1>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <p class="mb-6 text-lg">Have questions about PETSAppeal? We’d love to hear from you. Fill out the form below and we’ll get back to you shortly.</p>

    <form method="POST" action="{{ route('public.contact.submit') }}" class="space-y-6">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium mb-1">Your Name</label>
            <input type="text" id="name" name="name" required
                   class="w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-black dark:text-white px-3 py-2">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium mb-1">Email Address</label>
            <input type="email" id="email" name="email" required
                   class="w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-black dark:text-white px-3 py-2">
        </div>

        <div>
            <label for="message" class="block text-sm font-medium mb-1">Message</label>
            <textarea id="message" name="message" rows="5" required
                      class="w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-black dark:text-white px-3 py-2"></textarea>
        </div>

        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Send Message
        </button>
    </form>
@endsection
