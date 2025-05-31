<div x-data="{ open: false }" class="relative mb-6">
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
