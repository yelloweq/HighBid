<x-app-layout>
    <div id="content" class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if (!empty($auctions) && $auctions->count() > 0)
            <div class="my-6 text-2xl text-gray-100">
                {{ __('Your auctions') }}
            </div>
            <x-auction-grid :auctions=$auctions />
        @else
            <div class="flex align-middle justify-center items-center h-[50vh] text-2xl text-gray-100">
                {{ __('You do not have any active auctions yet') }}
            </div>
        @endif
    </div>
</x-app-layout>
