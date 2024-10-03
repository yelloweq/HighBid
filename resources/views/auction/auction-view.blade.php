<x-app-layout>
    @php
        //temporary rating
        $rating = 0;
    @endphp

    @push('styles')
        <style>
            #message.htmx-added {
                opacity: 0;
            }

            #message.htmx-swapping {
                opacity: 0;
                transition: opacity 500ms ease-out;
            }

            #message {
                opacity: 1;
                transition: opacity 500ms ease-in;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            .new-bid {
                animation: fadeIn 1s ease-in-out;
            }
        </style>
    @endpush

    <div class="max-w-11xl mx-auto px-4 sm:px-6 lg:px-8 text-white">
        <div class="py-6">
            <!-- Breadcrumb -->
            <nav aria-label="Breadcrumb" class="text-gray-400 text-sm">
                <ol class="list-none p-0 inline-flex">
                    <li class="flex items-center cursor-pointer" hx-get="{{ route('auctions', ['category' => 'all']) }}"
                        hx-trigger="click" hx-target="body" hx-push-url="true">
                        Auctions
                        <i class="fas fa-chevron-right mx-2 text-gray-400">
                        </i>
                    </li>
                    @if (!empty($auction->category) && !empty($auction->category->parent) && $auction->category->parent_id != null)
                        <li class="flex items-center cursor-pointer"
                            hx-get="{{ route('auctions', ['category' => $auction->category->parent->id]) }}"
                            hx-trigger="click" hx-target="body" hx-push-url="true">
                            {{ $auction->category->parent->name }}
                            <i class="fas fa-chevron-right mx-2 text-gray-400">
                            </i>
                        </li>
                        <li class="flex items-center cursor-pointer"
                            hx-get="{{ route('auctions', ['category' => $auction->category->id]) }}" hx-trigger="click"
                            hx-target="body" hx-push-url="true">
                            {{ $auction->category->name }}
                        </li>
                    @elseif (!empty($auction->category) && $auction->category->parent_id == null)
                        <li class="flex items-center cursor-pointer"
                            hx-get="{{ route('auctions', ['category' => $auction->category->id]) }}" hx-trigger="click"
                            hx-target="body" hx-push-url="true">
                            {{ $auction->category->name }}
                        </li>
                    @else
                        <li class="flex items-center cursor-pointer"
                            hx-get="{{ route('auctions', ['category' => 'all']) }}" hx-trigger="click" hx-target="body"
                            hx-push-url="true">
                            All
                        </li>
                    @endif
                </ol>
            </nav>
            <!-- Product Details -->
            <div class="md:flex md:space-x-6 md:py-8">
                <!-- Images -->
                <div class="relative md:w-1/2 space-y-4">
                    <div class="grid grid-rows-2 grid-cols-2 ">
                        @if ($auction->images->count() <= 4)
                            @for ($index = 0; $index < $auction->images->count(); $index++)
                                <div class="zoom-container mr-4 my-2 bg-gray-900">
                                    <img alt="Product image" class="object-contain w-96 h-96 mx-auto"
                                         src="{{ asset('storage/' . $auction->images->get($index)->path) }}"/>

                                </div>
                                @if ($auction->images->get($index)->flagged)
                                    <p
                                        class="text-white absolute top-1/4 left-1/4 -translate-x-1/2 -translate-y-1/2 bg-red-500 p-4">
                                        Image contains sensitive material</p>
                                @endif
                            @endfor
                        @else
                            @for ($index = 0; $index < $auction->images->count(); $index++)
                                <div class="zoom-container mr-4 my-2">
                                    <img alt="Product image" class="object-contain w-96 h-96 mx-auto"
                                         src="{{ asset('storage/' . $auction->images->get($index)->path) }}"/>
                                </div>
                                @if ($auction->images->get($index)->flagged)
                                    <p
                                        class="text-white absolute top-1/4 left-1/4 -translate-x-1/2 -translate-y-1/2 bg-red-500 p-4">
                                        Image contains sensitive material</p>
                                @endif
                            @endfor
                        @endif
                    </div>

                </div>
                <!-- Content -->
                <div class="md:w-1/2 grid grid-cols-2">
                    @if (Auth::user() && Auth::user()->id === $auction->seller->id)
                        <div class="text-3xl font-semibold flex flex-col col-span-2 mb-8">
                            <div class="flex justify-between items-center">
                                {{ $auction->title }}
                                <div hx-get="{{ route('auction.edit.form', $auction) }}" hx-swap="outerHTML"
                                     hx-target="body" hx-trigger="click"><i class="fa-solid fa-pen-to-square "
                                                                            style="color: #ffffff;"></i></div>
                            </div>

                            @if (empty(json_decode($auction->images->first()?->metadata, true)))
                                <div class="text-lg font-normal  text-red-400">
                                    Warning: This auction contains flagged images
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-3xl font-semibold col-span-2 mb-8">

                            {{ $auction->title }}

                            @if ($auction->images->count() > 0 && $auction->images->contains('flagged', true))
                                <div class="text-lg font-normal  text-red-400">
                                    Warning: This auction contains unverified images
                                </div>
                            @endif
                        </div>
                    @endif
                    <div class="auction-info">

                        <div class="flex items-center mb-4">
                            <span>@</span><span class="mr-2">{{ $auction->seller->username }}</span>

                            <div class="text-yellow-400">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($rating >= $i)
                                        <i class="fas fa-star"></i> <!-- Full star -->
                                    @elseif ($rating >= $i - 0.5)
                                        <i class="fas fa-star-half-alt"></i> <!-- Half star -->
                                    @else
                                        <i class="far fa-star"></i> <!-- Empty star -->
                                    @endif
                                @endfor
                            </div>
                            <span class="text-gray-400 text-sm ml-3">
                                (0)
                            </span>
                        </div>
                        <p class="text-gray-400 mb-4">
                            {{ $auction->description }}
                        </p>
                        <div class="mb-8">
                            <h2 class="font-semibold mb-1">
                                Features
                            </h2>
                            <p class="text-gray-400">
                                {{ $auction->features }}
                            </p>
                        </div>

                        <div class="flex items-center mb-4">
                            <span class="text-2xl font-semibold">
                                <div hx-get={{ route('auction.latestBid', $auction) }} hx-swap="innerHTML"
                                     hx-trigger="load, every 15s">
                                </div>
                            </span>
                        </div>

                        @if ($auction->seller->id == auth()->id() || $auction->winner?->id == auth()->id())
                            <div class="mb-4">
                                <ul>
                                    <li>This auction is currently:
                                        <div
                                            class="inline-block ml-2 text-green-400 animate-bounce">
                                            {{ $auction->status }}
                                            <div>
                                                <li>
                                </ul>
                            </div>
                        @endif
                        @if ($auction->winner_id == auth()->id() && auth()->check())
                            <div class="flex items-center mb-4">
                                <form action="{{ route('payment.checkout', $auction) }}" method="POST"
                                      hx-boost="false">
                                    @csrf
                                    <button @if ($auction->status === 'Closed' && $auction->winner->id) disabled @endif
                                    class="px-6 py-3 border bg-blue-accent border-black text-sm disabled:bg-opacity-60 w-full">
                                        @if ($auction->status === 'Closed')
                                            PAID
                                        @else
                                            Proceed to payment
                                        @endif
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="flex items-center mb-4">
                                <form hx-post="{{ route('auction.bid', $auction) }}" hx-target="#messages"
                                      hx-swap="innterHTML" hx-boost="false" class="w-full" id="bid-form">
                                    @csrf

                                    <div class="flex align-middle items-center justify-between mb-4">
                                        <input class="text-black disabled:opacity-80" type="text" name="bid"
                                               placeholder="£" autocomplete="false" required pattern="^\d+(\.\d{1,2})?$"
                                               id="bid"
                                               @if ($auction->end_time->lte(Carbon\Carbon::now())) disabled @endif
                                        />

                                        <label class="flex items-center cursor-pointer">
                                            <input type="checkbox" name="auto_bid" id="auto_bid" class="mr-2"
                                                   value="1" onchange="toggleAutoBidLabel(this)">
                                            <span id="auto_bid_label" class="w-28">Autobid: OFF</span>
                                        </label>
                                    </div>
                                    <button
                                        class="px-6 py-3 border bg-blue-accent border-black text-sm disabled:bg-opacity-60 w-full"
                                        @if (!Auth::check() || $auction->end_time->lte(Carbon\Carbon::now())) disabled @endif>
                                        @if (Auth::check())
                                            PLACE BID
                                        @else
                                            Login to bid
                                        @endif
                                    </button>
                                </form>
                            </div>
                        @endif

                        <div id="messages" class="w-full min-h-14 mb-2"></div>

                        @if ($auction->seller->id != auth()->id())
                            <p class="text-blue-400 hover:underline cursor-pointer">
                                <a href="/messages/{{ $auction->seller->id }}" hx-boost="false" hx-push-url="true">
                                    Message seller for
                                    more
                                    information </a>
                            </p>
                        @endif
                        <p class="text-gray-600 text-sm mb-4">
                            <span>Ends on {{ $auction->end_time->isoFormat('MMMM Do YYYY, h:mm a') }}.</span>
                        </p>
                        <p class="text-gray-600 text-sm">
                            {{-- additional info text here --}}
                        </p>
                    </div>
                    <div class="bid-history">
                        <div id="watchersCount" hx-get="{{ route('auction.watchers', ['auction' => $auction]) }}"
                             hx-trigger="load, every 10s"></div>

                        <h4 class=" text-lg text-center mt-4">Recent bids</h4>
                        <div hx-get="{{ route('auction.recentBids', $auction) }}"
                             hx-trigger="load, every 10s, from:closest(#bid-form)" hx-target="this"
                             class="relative flex flex-col w-full h-full overflow-scroll text-white bg-blue-primary shadow-md bg-clip-border rounded-xl m-4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script>
    function toggleAutoBidLabel(checkbox) {
        const label = document.getElementById('auto_bid_label');
        const bid = document.getElementById('bid');
        if (checkbox.checked) {
            label.innerHTML = 'Autobid: ON';
            bid.placeholder = "£ (MAX BID)";
        } else {
            label.innerHTML = 'Autobid: OFF';
            bid.placeholder = "£";
        }
    }

    document.addEventListener('htmx:afterSwap', function (event) {
        if (!event.detail.target.matches('.top-bid')) return;

        const newBidAmount = event.detail.target.getAttribute('data-bid-amount');
        const lastBidAmount = sessionStorage.getItem('lastBidAmount');

        if (newBidAmount !== lastBidAmount) {
            event.detail.target.classList.add('new-bid');
            sessionStorage.setItem('lastBidAmount', newBidAmount);
        }
    });
</script>
