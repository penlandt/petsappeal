<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Create Your Company
        </h2>
    </x-slot>

    <div class="py-10 px-4 max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
            <form method="POST" action="{{ route('companies.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-200">Company Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full mt-1 rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                </div>

                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-200">Address</label>
                    <input type="text" name="address" value="{{ old('address') }}" required
                        class="w-full mt-1 rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-200">City</label>
                        <input type="text" name="city" value="{{ old('city') }}" required
                            class="w-full mt-1 rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>
                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-200">State</label>
                        <select name="state" required
                            class="w-full mt-1 rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="">Select</option>
                            @foreach (['AL','AK','AZ','AR','CA','CO','CT','DE','FL','GA','HI','ID','IL','IN','IA','KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY','DC'] as $abbr)
                                <option value="{{ $abbr }}" @if(old('state') == $abbr) selected @endif>{{ $abbr }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-200">Postal Code</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" required
                            class="w-full mt-1 rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-200">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            class="w-full mt-1 rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>
                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-200">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full mt-1 rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>
                </div>

                <div class="pt-6 text-right">
                    <x-primary-button>Create Company</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
