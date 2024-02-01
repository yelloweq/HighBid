<div class="auction-card flex min-w-48 justify-center align-middle">
    <a 
        hx-get="{{ route('auction.view', ['auction' => $auction]) }}"
        hx-target="main"
        hx-swap="outerHTML"
        hx-push-url="true"
        >
        <div class="auction-card__title">
            {{ $auction->title }}
        </div>
        <div class="auction-card__description">
            {{ $auction->description }}
        </div>
        <div class="auction-card__price">
            {{ $auction->price }}
        </div>
        <div class="auction-card__delivery">
            {{ $auction->deliveryType }}
        </div>
        <div class="auction-card__created-at">
            {{ $auction->created_at->toDateString() }}
        </div>
    </a>
</div>