@props(['showRating' => false, 'rating' => 4.3, 'auction'])

<div class="text-wrap w-full max-w-sm  border text-white rounded-lg shadowbg-gray-800 border-gray-700 bg-gray-800 text-center flex flex-col justify-between overflow-hidden">
    <div hx-get="{{ route('auction.view', ['auction' => $auction])}}" 
        hx-target="body" hx-swap="outerHTML" hx-push-url="true"
    class="flex items-center justify-center overflow-hidden max-h-[200px] cursor-pointer">
        @if ($auction->images->count() > 1)
            <img class="object-contain max-w-sm rounded-lg" src="{{ asset('storage/'.$auction->images->first()->path ) }}" alt="">
        @endif
        
    </div>
    <div class="px-5 pb-5">
        <h5 class="text-xl font-semibold tracking-tight text-white text-wrap overflow-hidden">{{$auction->title}}</h5>
        <div class="flex items-center mt-2.5 mb-5">
            @if ($showRating && $rating > 0)
            <div class="flex items-center space-x-1 rtl:space-x-reverse">
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
            </div>
            <span class="text-xs font-semibold px-2.5 py-0.5 rounded bg-blue-200 text-blue-800 ms-3">{{$rating}}</span>
            @endif
        </div>
        <div class="flex items-center justify-between">
            <span class="text-2xl font-bold text-white">
                    <div hx-get={{ route('auction.latestBid', $auction) }} hx-swap="innerHTML"
                        hx-trigger="load, every 15s">
                    </div>
            </span>
            <button hx-get="{{ route('auction.view', ['auction' => $auction])}}" 
                hx-target="body" hx-swap="outerHTML" hx-push-url="true"
                class="focus:ring-4 focus:outline-none  font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800">View</button>
        </div>
    </div>
</div>