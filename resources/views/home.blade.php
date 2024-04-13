@php
    $displayAuctions = $totalAuctions;
    if ($totalAuctions >= 1000) {
        $displayAuctions = floor($totalAuctions / 1000) . 'K+';
    }

    $displayBids = $totalBids;
    if ($totalBids >= 1000) {
        $displayBids = floor($totalBids / 1000) . 'K+';
    }

    $displayUsers = $totalUsers;
    if ($totalUsers >= 1000) {
        $displayUsers = floor($totalUsers / 1000) . 'K+';
    }
@endphp

<x-app-layout>
<section class="text-center mb-12 text-white max-w-11xl mx-auto">
    <h2 class="text-4xl md:text-5xl lg:text-6xl text-transparent font-inter font-semibold text-white p-4">
        Explore <span class="text-transparent bg-gradient-to-r from-blue-accent via-blue-400 to-indigo-700 bg-clip-text"> authentic </span> auctions with just one click
    </h2>
    <p class="text-gray-400 mb-6">
        Our automated system makes it easy to find the best deals on the items you love.
    </p>
    <div class="flex justify-center space-x-4">
        <button class="bg-blue-600 px-6 py-3 rounded-md text-white font-semibold hover:bg-blue-700" hx-get="{{ route('auctions')}}" hx-target="body" hx-trigger="click" hx-push-url="true">
            Explore
        </button>
        <button
        hx-get="{{ route('auction.create')}}" hx-target="body" hx-trigger="click" hx-push-url="true"
            class="bg-transparent px-6 py-3 rounded-md text-white font-semibold border border-blue-600 hover:border-blue-700 hover:text-blue-500">
            Create
        </button>
    </div>
</section>
<section class="flex justify-between items-center mb-12 max-w-11xl mx-auto">
    <div class="flex space-x-8">
        <div class="text-center">
            <h3 class="text-3xl font-bold text-blue-500">
                {{$displayUsers}}
            </h3>
            <p class="text-gray-400">
                Users
            </p>
        </div>
        <div class="text-center">
            <h3 class="text-3xl font-bold text-blue-500">
                {{$displayBids}}
            </h3>
            <p class="text-gray-400">
                Bids
            </p>
        </div>
        <div class="text-center">
            <h3 class="text-3xl font-bold text-blue-500">
                {{$displayAuctions}}
            </h3>
            <p class="text-gray-400">
                Auctions
            </p>
        </div>
    </div>
    <div class="flex items-center">
        <i class="fas fa-certificate text-blue-500 mr-2">
        </i>
        <p class="text-gray-400">
            More active costumer
        </p>
    </div>
</section>
<section class="mb-12 max-w-11xl mx-auto">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-bold text-yellow-400 limited-auction">
            Limited Auctions
            <span class="text-yellow-400 countdown-timer">
                {{-- countdown here --}}
            </span>
        </h3>
    </div>
    <div hx-get={{ route('auctions.limited' )}} hx-target="this" hx-swap="outerHTML" hx-trigger="load"></div>
</section>
</x-app-layout>


<script>
    document.addEventListener('htmx:load', function() {
        function updateCountdown() {
            const countdowns = document.querySelectorAll('.countdown');
            countdowns.forEach(countdown => {
                const endTime = new Date(countdown.dataset.endTime).getTime();
                const now = new Date().getTime();
                const distance = endTime - now;

                if (distance < 0) {
                    countdown.textContent = "Auction ended";
                } else {
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    countdown.textContent = `${hours}h ${minutes}m ${seconds}s`;
                }
            });
        }

        document.querySelectorAll('.countdown').forEach(countdown => {
            const intervalId = setInterval(updateCountdown, 1000);
            countdown.dataset.intervalId = intervalId; // Store interval ID in dataset for potential clearance
        });
    });

    document.body.addEventListener('htmx:afterSwap', function(event) {
        if (event.detail.target.classList.contains('countdown-timer')) {
            // Assuming 'updateCountdown' function is globally available
            updateCountdown();
        }
    });
</script>

