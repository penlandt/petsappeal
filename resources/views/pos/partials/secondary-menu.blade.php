<div class="flex items-center">
    <!-- Register Menu Item -->
    <a href="{{ url('/pos') }}"
       class="text-gray-800 dark:text-gray-100 hover:underline">
        Register
    </a>

    <!-- HARD SPACE FIX -->
    <span class="inline-block w-4"></span>

    <!-- Returns Menu Item -->
    <a href="{{ route('pos.returns.index') }}"
       class="text-gray-800 dark:text-gray-100 hover:underline">
        Returns
    </a>

    <!-- HARD SPACE FIX -->
    <span class="inline-block w-4"></span>

    <!-- Products Menu Item -->
    <a href="{{ route('pos.products') }}"
       class="text-gray-800 dark:text-gray-100 hover:underline">
        Products
    </a>

    <!-- HARD SPACE FIX -->
    <span class="inline-block w-4"></span>

    <!-- Inventory Dropdown -->
    <div x-data="{ open: false }"
         class="relative"
         @mouseenter="open = true"
         @mouseleave="open = false">
        <button class="text-gray-800 dark:text-gray-100 hover:underline focus:outline-none">
            Inventory
        </button>

        <div x-show="open"
             x-transition
             class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded shadow-lg z-10"
             @mouseenter="open = true"
             @mouseleave="open = false">
            <a href="{{ route('inventory.countSheet', ['location' => auth()->user()->selected_location_id]) }}"
               class="block px-4 py-2 text-sm text-gray-800 dark:text-gray-100 hover:text-white"
               style="background-color: transparent;"
               onmouseover="this.style.backgroundColor='#3b82f6'; this.style.color='white';"
               onmouseout="this.style.backgroundColor=''; this.style.color='';">
                Count Sheet
            </a>
        </div>
    </div>
</div>
