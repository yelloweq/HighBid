<div class="bg-red-500 hover:cursor-pointer mb-4 flex max-h-[200px]"
    hx-get="{{ route('auction.view', ['auction' => $auction]) }}"
    hx-target="main"
    hx-swap="outerHTML"
    hx-push-url="true"
    >
    <div class="rounded-md bg-yellow-400 max-h-fit">
        <div class="auction-card__image">
            <img src="https://i.ebayimg.com/thumbs/images/g/xGQAAOSwPsRlwlJA/s-l300.webp" class="object-contain w-[227px] h-[227px]" alt="{{ $auction->title }}">
        </div>
    </div>
    <div class="block bg-gray-50 w-1/2 ms-10">
        <div class="auction-card__title bg-red-300 font-medium text-xl mb-2">
            {{ $auction->title }}
        </div>
        <div class="auction-card__content flex justify-between">
            <div>
                <div class="auction-card__price font-semibold text-2xl">
                    <x-money amount="{{$auction->price}}" currency="GBP" />
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
                        {{ $auction->timeRemaining() }}
                </div>
            </div>
        </div>
    </div>
</div>


