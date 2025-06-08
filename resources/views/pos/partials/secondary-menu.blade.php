<div class="flex space-x-4 text-sm font-medium">
    <!-- Register -->
    <a href="{{ url('/pos') }}"
       class="text-gray-800 dark:text-gray-100 hover:underline">
        Register
    </a>

    <!-- Returns -->
    <a href="{{ route('pos.returns.index') }}"
       class="text-gray-800 dark:text-gray-100 hover:underline">
        Returns
    </a>

    <!-- Products -->
    <a href="{{ route('pos.products') }}"
       class="text-gray-800 dark:text-gray-100 hover:underline">
        Products
    </a>

    <!-- Inventory Dropdown -->
    <div x-data="{ openInventory: false }" class="relative">
        <button @click="openInventory = !openInventory"
                class="text-gray-800 dark:text-gray-100 hover:underline focus:outline-none">
            Inventory
        </button>
        <div x-show="openInventory"
             @click.away="openInventory = false"
             x-transition
             class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded shadow-lg z-50">
            <a href="{{ route('inventory.countSheet', auth()->user()->selected_location_id) }}"
               class="block px-4 py-2 text-sm text-gray-800 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700">
                Count Sheet
            </a>
        </div>
    </div>

    <!-- Reports Dropdown -->
    <div x-data="{ openReports: false }" class="relative">
        <button @click="openReports = !openReports"
                class="text-gray-800 dark:text-gray-100 hover:underline focus:outline-none">
            Reports
        </button>
        <div x-show="openReports"
             @click.away="openReports = false"
             x-transition
             class="absolute left-0 mt-2 w-56 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded shadow-lg z-50">
            <a href="{{ route('pos.reports.end_of_day') }}"
               target="_blank"
               class="block px-4 py-2 text-sm text-gray-800 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700">
                End of Day Report
            </a>
        </div>
    </div>
</div>
