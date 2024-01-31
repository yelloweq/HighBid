<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-wrap flex-grow-0 justify-start align-middle gap-3">
                    @foreach ($auctions as $auction)
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
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
