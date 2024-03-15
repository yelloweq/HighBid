<x-app-layout>
    {{-- <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 text-wrap">
                {{ $auction->title }}
                {{ $auction->description }}
                <div hx-get={{ route('auction.latestBid', $auction) }} hx-swap="innerHTML" hx-trigger="load delay:3s">
                    
                </div>
                
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
                @if ($auction->end_time < now()) disabled @endif>
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
</div> --}}

    @php
        //temporary rating
        $rating = 3.2;
    @endphp

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
                                        src="{{ asset('storage/' . $auction->images->get($index)->path) }}" />
                                </div>
                            @endfor
                        @else
                            @for ($index = 0; $index < 3; $index++)
                                <div class="zoom-container mr-4 my-2">
                                    <img alt="Product image" class="w-full"
                                        src="{{ asset('storage/' . $auction->images->get($index)->path) }}" />
                                </div>
                            @endfor
                            <div class="relative mr-4 my-2">
                                <img alt="Product image"
                                    class="w-full"src="{{ asset('storage/' . $auction->images->get($index)->path) }}" />
                                @if ($auction->images()->count() > 4)
                                    <span
                                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-gray-800 px-6 py-4 bg-opacity-80">{{ $auction->images()->count() - 4 }}+</span>
                                @endif
                            </div>
                        @endif


                    </div>

                    <div class="flex -mx-2">

                    </div>
                </div>
                <!-- Content -->
                <div class="md:w-1/2">
                    <h1 class="text-3xl font-semibold mb-2">
                        {{ $auction->title }}
                    </h1>
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
                            (753)
                        </span>
                        <div class="ml-auto" id="watchersCount"
                            hx-get="{{ route('auction.watchers', ['auction' => $auction]) }}"
                            hx-trigger="load, every 10s"></div>
                    </div>
                    <p class="text-gray-400 mb-4">
                        {{ $auction->description }}
                    </p>
                    <div class="mb-4">
                        <h2 class="font-semibold mb-1">
                            Targets
                        </h2>
                        <p class="text-gray-400">
                            Dryness, Signs of Aging
                        </p>
                    </div>
                    <div class="mb-4">
                        <h2 class="font-semibold mb-1">
                            Suited to
                        </h2>
                        <p class="text-gray-400">
                            All Skin Types
                        </p>
                    </div>
                    <div class="mb-4">
                        <h2 class="font-semibold mb-1">
                            Format
                        </h2>
                        <p class="text-gray-400">
                            Water-based Serum
                        </p>
                    </div>
                    <div class="mb-4">
                        <h2 class="font-semibold mb-1">
                            Key ingredients
                        </h2>
                        <p class="text-gray-400">
                            Hyaluronic acid, ceramides, pro-vitamin B5
                        </p>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-2xl font-semibold">
                            <div hx-get={{ route('auction.latestBid', $auction) }} hx-swap="innerHTML"
                                hx-trigger="load, every 15s">
                            </div>
                        </span>
                    </div>
                    <span>{{ $auction->end_time }}</span>
                    <div class="flex items-center mb-4">
                        <form hx-post="{{ route('auction.bid', $auction) }}" hx-target="body" hx-swap="outerHTML"
                            hx-boost="false">
                            @csrf
                            <input class="text-black" type="text" name="bid" placeholder="£" autocomplete="false"
                                required pattern="^\d+(\.\d{1,2})?$" @if ($auction->end_time < now()) disabled @endif>
                            <button class="ml-4 px-6 py-3 border bg-blue-accent border-black text-sm">
                                PLACE BID
                            </button>
                        </form>

                    </div>
                    <p class="text-gray-600 text-sm mb-4">
                        Free shipping on orders over 25 GBP
                        <a class="text-blue-600" href="#">
                            FAQs
                        </a>
                        .
                    </p>
                    <p class="text-gray-600 text-sm">
                        365 day returns.
                        <a class="text-blue-600" href="#">
                            Return Policy
                        </a>
                        .
                    </p>
                    <div
                        class="grid lg:grid-rows-2 lg:grid-cols-5 sm:grid-rows-3 sm:grid-cols-3 items-center mt-4 bg-blue-secondary justify-center rounded-md flex-wrap p-6 text-center">
                        <div class="text-gray-400 flex flex-col items-center mb-4">
                            <dt class="mb-2 min-w-24">water-resistant</dt>
                            <dd><i class="fa-regular fa-circle-check fa-lg"></i></dd>
                        </div>
                        <div class="text-gray-400 flex flex-col items-center mb-4">
                            <dt class="mb-2 min-w-24">vegan</dt>
                            <dd><i class="fa-regular fa-circle-check fa-lg text-green-300"></i></dd>
                        </div>
                        <div class="text-gray-400 flex flex-col items-center mb-4">
                            <dt class="mb-2 min-w-24">ph</dt>
                            <dd><i class="fa-regular fa-circle-check fa-lg"></i></dd>
                        </div>
                        <div class="text-gray-400 flex flex-col items-center mb-4">
                            <dt class="mb-2 min-w-24">ph</dt>
                            <dd><i class="fa-regular fa-circle-xmark fa-lg text-red-300"></i></dd>
                        </div>
                        <div class="text-gray-400 flex flex-col items-center mb-4">
                            <dt class="mb-2 min-w-24">ph</dt>
                            <dd><i class="fa-regular fa-circle-check fa-lg"></i></dd>
                        </div>
                        <div class="text-gray-400 flex flex-col items-center mb-4">
                            <dt class="mb-2 min-w-24">ph</dt>
                            <dd><i class="fa-regular fa-circle-check fa-lg"></i></dd>
                        </div>
                        <div class="text-gray-400 flex flex-col items-center mb-4">
                            <dt class="mb-2 min-w-24">ph</dt>
                            <dd><i class="fa-regular fa-circle-check fa-lg"></i></dd>
                        </div>
                        <div class="text-gray-400 flex flex-col items-center mb-4">
                            <dt class="mb-2 min-w-24">ph</dt>
                            <dd><i class="fa-regular fa-circle-check fa-lg"></i></dd>
                        </div>
                        <div class="text-gray-400 flex flex-col items-center mb-4">
                            <dt class="mb-2 min-w-24">ph</dt>
                            <dd><i class="fa-regular fa-circle-check fa-lg"></i></dd>
                        </div>
                        <div class="text-gray-400 flex flex-col items-center mb-4">
                            <dt class="mb-2 min-w-24">ph</dt>
                            <dd><i class="fa-regular fa-circle-check fa-lg"></i></dd>
                        </div>
                        <div class="text-gray-400 flex flex-col items-center mb-4">
                            <dt class="mb-2 min-w-24">ph</dt>
                            <dd><i class="fa-regular fa-circle-check fa-lg"></i></dd>
                        </div>
                        <div class="text-gray-400 flex flex-col items-center mb-4">
                            <dt class="mb-2 min-w-24">ph</dt>
                            <dd><i class="fa-regular fa-circle-check fa-lg"></i></dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
