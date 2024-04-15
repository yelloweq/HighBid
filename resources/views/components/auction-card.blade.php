<div class="auction-card bg-blue-secondary hover:cursor-pointer mb-4 flex max-h-[227px] border-b-8  border-blue-thirtiary text-white"
    data-end-time="{{ $auction->end_time }}"
    hx-get="{{ route('auction.view', ['auction' => $auction]) }}"
    hx-target="body"
    hx-swap="outerHTML"
    hx-push-url="true"
    >
    <div class="overflow-hidden text-center item-center align-middle ">
        <div class="auction-card__image w-[227px] h-[227px]">
            {{-- <img src="https://i.ebayimg.com/thumbs/images/g/xGQAAOSwPsRlwlJA/s-l300.webp"
                class="w-[227px] h-[227px] object-scale-down" alt="{{ $auction->title }}"> --}}
            @if ($auction->images->first())
            <img alt="Product image" class="object-contain text-center" src="{{ asset('storage/'.$auction->images->first()->path ) }}"/>
            @else
            <div class="w-[227px] h-[227px] align-middle justify-center items-center flex overflow-hidden">
                <span class="z-10">NO IMAGE</span>
            </div>
            @endif
        </div>
    </div>
    <div class="block  w-1/2 ms-2">
        <div class="auction-card__title font-medium text-2xl mb-2">
            {{ $auction->title }}
        </div>
        <div class="auction-card__content flex justify-between">
            <div>
                <div id="latest-bid-{{ $auction->id }}"
                class="auction-card__price font-medium text-xl transition-all duration-300 ease-in-out"
                    hx-get={{ route('auction.latestBid', $auction) }} hx-swap="innerHTML" hx-target="#latest-bid-{{ $auction->id }}" hx-push-url="false"
                    hx-trigger="intersect, every 60s">
                    <div class="max-w-full animate-pulse">
                        <div
                        class="block w-20 h-6 mb-4 font-sans text-5xl antialiased font-semibold leading-tight tracking-normal bg-gray-400 rounded-full text-inherit">
                        &nbsp;
                      </div>
                    </div>
                </div>
                <div class="auction-card__delivery">
                    {{ $auction->delivery_type }}
                </div>
            </div>
            <div>
                <div class="auction-card__bids">
                    {{ $auction->bids()->count() }} bids
                </div>
                <div class="auction-card__created-at">
                    <span class="countdown"></span>
                </div>
            </div>
        </div>
    </div>
</div>
