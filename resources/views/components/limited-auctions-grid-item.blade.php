@props(['auction'])

<div class="bg-gray-800 p-4 rounded-lg limited-auction-card">
    <img alt="Product image" class="object-contain w-96 h-96 mx-auto" src="{{ asset('storage/' . $auction->images->first->path) }}" />
    <h4 class="text-lg font-semibold mb-2 text-white">
        {{$auction->title}}
    </h4>
    <p class="text-gray-400 text-sm mb-4">
        <span>@</span>{{ $auction->seller->username}}
    </p>
    <div class="flex justify-between items-center mb-4 text-center">
        <span class="text-blue-500 w-32 countdown" data-end-time="{{ $auction->end_time }}
            ">
            {{$auction->end_time}}
            {{-- make this a count down which should be in the format 03:30:24 (h:m:s) use $auction->end_time --}}
        </span>
        <span class="text-gray-400 w-32">
            Current bid
        </span>
        <span class="text-white w-32" id="latest-bid-{{ $auction->id }}" hx-get={{ route('auction.latestBid', $auction) }} hx-swap="innerHTML" hx-target="#latest-bid-{{ $auction->id }}" hx-push-url="false"
            hx-trigger="intersect, every 60s">
            <div class="max-w-full animate-pulse">
                <div
                class="block w-20 h-6 mb-4 font-sans text-5xl antialiased font-semibold leading-tight tracking-normal bg-gray-400 rounded-full text-inherit">
                &nbsp;
              </div>
            </div>
        </span>
    </div>
    <button type="button" class="w-full bg-blue-600 py-2 rounded-md text-white font-semibold hover:bg-blue-700" hx-get="{{ route('auction.view', ['auction' => $auction]) }}" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
        Place a Bid
    </button>
</div>
