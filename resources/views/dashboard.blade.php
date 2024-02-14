<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div id="content" class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="my-6 text-gray-900 dark:text-gray-100">
                {{ __("Your auctions") }}
            </div>
            <x-auction-grid :auctions=$auctions />
        </div>
        
    </div>
</x-app-layout>
