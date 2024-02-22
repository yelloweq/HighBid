<x-app-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 text-wrap">
                {{ $auction->title }}
                {{ $auction->description }}
                Current bid: 
                @if ($auction->bids->count() <= 0) 
                    <x-money :amount="$auction->price" /> 
                @else 
                    <x-money :amount="$auction->getHighestBid()" /> @endif
                {{ $auction->end_time }}
                {{ $auction->delivery_type }}
                {{ $auction->auction_type }}

                {{ $auction->seller->email }}

                
                @foreach ($auction->images as $image)
                <img src="{{ asset('storage/'.$image->path) }}" alt="image" class="w-1/4">
                @endforeach
            </div>
            <form hx-post="{{ route('auction.bid', $auction) }}" hx-target="body" hx-swap="outerHTML" hx-boost="false">
                @csrf
                <input type="number" name="bid" placeholder="£" autocomplete="false" required pattern="^\d+(\.\d{1,2})?$" step="0.01" 
                @if($auction->end_time > now()) disabled @endif>
                <x-primary-button>Place bid</x-primary-button>
            </form>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
