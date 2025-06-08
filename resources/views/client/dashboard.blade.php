<x-client-layout>
    <h1 class="text-2xl font-bold mb-4">Welcome to Your Dashboard</h1>

    <p class="mb-2">You're logged in as <strong>{{ auth()->guard('client')->user()->email }}</strong>.</p>

    <p>This is your client portal. In the future, you’ll be able to book appointments, view history, and manage pets here.</p>
</x-client-layout>
