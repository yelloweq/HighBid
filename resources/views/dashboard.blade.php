<x-app-layout>
    <div id="content" class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{-- @if (!empty($auctions) && $auctions->count() > 0)
            <div class="my-6 text-2xl text-gray-100">
                {{ __('Your auctions') }}
            </div>
            <x-auction-grid :auctions=$auctions />
        @else
            <div class="flex align-middle justify-center items-center h-[50vh] text-2xl text-gray-100">
                {{ __('You do not have any active auctions yet') }}
            </div>
        @endif --}}


        <div class="flex items-center justify-center text-center ">
            <div class="text-3xl text-gray-100">
                <strong>{{ __('Your auctions') }}</strong>
            </div>
        </div>
        <div class="flex items-center justify-center py-4 md:py-8 flex-wrap">
            <x-pill-button :active="request()->routeIs('dashboard')">Active</x-pill-button>
            <x-pill-button :active="request()->routeIs('dashboard')">Inactive</x-pill-button>
            <x-pill-button :active="request()->routeIs('dashboard')">Won</x-pill-button>
            <x-pill-button :active="request()->routeIs('dashboard')">Bids</x-pill-button>
            <x-pill-button :active="request()->routeIs('dashboard')">Following</x-pill-button>
            {{-- htmx request from button to change filtering on page --}}
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach ($auctions as $auction)
                <x-card-with-button :auction=$auction />
            @endforeach
        </div>
        
</x-app-layout>
