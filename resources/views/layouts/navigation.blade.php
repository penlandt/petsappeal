<nav x-data="{ open: false }" class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 h-16">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                @auth
                    <!-- Company Dropdown -->
                    <div class="hidden sm:ml-10 sm:flex sm:items-center">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-900 hover:text-gray-700 dark:hover:text-white focus:outline-none transition">
                                    <div>Company</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('companies.index')">Company Info</x-dropdown-link>
                                <x-dropdown-link :href="route('clients.index')">Clients</x-dropdown-link>
                                <x-dropdown-link :href="route('pets.index')">Pets</x-dropdown-link>
                                <x-dropdown-link :href="route('services.index')">Services</x-dropdown-link>
                                <x-dropdown-link :href="route('locations.index')">Locations</x-dropdown-link>
                                <x-dropdown-link :href="route('staff.index')">Staff</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Modules Dropdown -->
                    <div class="hidden sm:ml-10 sm:flex sm:items-center">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-900 hover:text-gray-700 dark:hover:text-white focus:outline-none transition">
                                    <div>Modules</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <!-- Retail replaced by single Register link -->
                                <x-dropdown-link :href="route('pos.index')">Point of Sale</x-dropdown-link>

                                <!-- Other Modules -->
                                <x-dropdown-link :href="route('schedule.index')">Grooming</x-dropdown-link>
                                <!-- Updated Boarding link -->
                                <x-dropdown-link :href="route('boarding.reservations.index')">Boarding</x-dropdown-link>
                                <x-dropdown-link :href="route('modules.daycare')">Daycare</x-dropdown-link>
                                <x-dropdown-link :href="route('modules.house')">House/Pet Sitting</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Reports Dropdown -->
                    <div class="hidden sm:ml-10 sm:flex sm:items-center">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-900 hover:text-gray-700 dark:hover:text-white focus:outline-none transition">
                                    <div>Reports</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('reports.recurring-conflicts')">Recur Appt Conflicts</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Tools Dropdown -->
                    <div class="hidden sm:ml-10 sm:flex sm:items-center">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-900 hover:text-gray-700 dark:hover:text-white focus:outline-none transition">
                                    <div>Tools</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('import_export.index')">Import / Export</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Admin Dropdown -->
                    @if(Auth::user()->is_admin)
                        <div class="hidden sm:ml-10 sm:flex sm:items-center">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-600 dark:text-red-400 bg-white dark:bg-gray-900 hover:text-red-800 dark:hover:text-white focus:outline-none transition">
                                        <div>Admin</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('admin.users')">Manage User Accounts</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif
                @endauth

                @guest
                    <div class="hidden sm:flex sm:ml-10 sm:items-center gap-x-10">
                        <a href="{{ route('public.about') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">About</a>
                        <a href="{{ route('public.pricing') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Pricing</a>
                        <a href="{{ route('public.contact') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Contact</a>
                    </div>
                @endguest
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-900 hover:text-gray-700 dark:hover:text-white focus:outline-none transition">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            @if (session()->has('impersonator_id'))
                                <x-dropdown-link :href="route('admin.stop-impersonating')" class="text-yellow-500">
                                    Stop Impersonating
                                </x-dropdown-link>
                            @endif

                            <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="space-x-4">
                        <x-nav-link :href="route('login')" :active="request()->routeIs('login')">Login</x-nav-link>
                        <x-nav-link :href="route('register')" :active="request()->routeIs('register')">Register</x-nav-link>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-300 hover:text-gray-500 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 focus:text-gray-500 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>
