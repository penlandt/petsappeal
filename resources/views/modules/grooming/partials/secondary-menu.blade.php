<div class="flex space-x-4 mb-6">
    <!-- Requests Button -->
    <a href="{{ route('appointments.approval.index') }}"
        class="relative px-4 py-2 rounded bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-indigo-500 hover:text-white transition inline-block">
        Requests
        @if ($pendingRequestsCount > 0)
            <span
                class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2"
                aria-label="{{ $pendingRequestsCount }} pending appointment requests"
            >
                {{ $pendingRequestsCount }}
            </span>
        @endif
    </a>

    <!-- Reports Dropdown -->
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open"
            class="px-4 py-2 rounded bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-indigo-500 hover:text-white transition">
            Reports
        </button>

        <div x-show="open" @click.away="open = false"
            class="absolute mt-2 w-64 bg-white dark:bg-gray-800 rounded shadow-lg z-10"
            x-transition>
            <a href="{{ route('reports.recurring-conflicts') }}"
            class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-indigo-500 hover:text-white transition">
                Recur Appt Conflicts
            </a>
        </div>
    </div>
</div>
