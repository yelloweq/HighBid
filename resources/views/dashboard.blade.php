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
            <form hx-post="{{ route('dashboard.search') }}" hx-target="[hx-dashboard-auctions-grid]" hx-swap="outerHTML">
                @csrf
                <input type="hidden" name="sort" id="sort" value="{{ request()->query('sort') ?? 'active' }}">
                <x-pill-button :active="request()->query('sort') == 'active'" hx-trigger="click"
                    hx-post-value="active" onclick="document.getElementById('sort').value = 'active';">
                    Active
                </x-pill-button>
                <x-pill-button :active="request()->query('sort') == 'inactive'" hx-trigger="click"
                    hx-post-value="inactive" onclick="document.getElementById('sort').value = 'inactive';">
                    Inactive
                </x-pill-button>
                <x-pill-button :active="request()->query('sort') == 'won'" hx-trigger="click"
                    hx-post-value="won" onclick="document.getElementById('sort').value = 'won';">
                    Won
                </x-pill-button>
                <x-pill-button :active="request()->query('sort') == 'bids'" hx-trigger="click"
                    hx-post-value="bids" onclick="document.getElementById('sort').value = 'bids';">
                    Bids
                </x-pill-button>
                <x-pill-button :active="request()->query('sort') == 'following'" hx-trigger="click"
                    hx-post-value="following" onclick="document.getElementById('sort').value = 'following';">
                    Following
                </x-pill-button>
            </form>
        </div>
        <x-dashboard-auction-grid :auctions="$auctions"> 

        </x-dashboard-auction-grid>
        
</x-app-layout>
