<ul class="w-full" hx-auction-grid
    @if ($auctions->hasMorePages()) hx-post={{ route('auction.search', ['page' => $auctions->currentPage() + 1]) }} hx-trigger="intersect once settle:0" hx-swap="afterend"
    hx-include="#sidebar-form"> @endif
    @foreach ($auctions as $auction)
    <li>
        <x-auction-card :auction=$auction />
    </li>
    @endforeach
</ul>

@push('scripts')
    @once
        <script>
            // Function to update countdown timer
            function updateCountdown(endTime, countdownElement) {
                const now = new Date().getTime();
                const distance = endTime - now;

                if (distance <= 0) {
                    countdownElement.innerHTML = "Auction ended";
                } else {
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    countdownElement.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                }
            }

            // Function to start countdown timer
            function startCountdown() {
                const auctionCards = document.querySelectorAll('.auction-card');

                auctionCards.forEach(card => {
                    const endTime = new Date(card.dataset.endTime).getTime();
                    const countdownElement = card.querySelector('.countdown');

                    // Update countdown initially
                    updateCountdown(endTime, countdownElement);

                    // Update countdown every second
                    setInterval(() => {
                        updateCountdown(endTime, countdownElement);
                    }, 1000);
                });
            }

            // Start countdown when the page is loaded
            document.addEventListener('DOMContentLoaded', startCountdown);
            document.addEventListener('htmx:afterSwap', function(event) {
                // Clear existing countdown intervals to prevent conflicts
                const auctionCards = document.querySelectorAll('.auction-card');
                auctionCards.forEach(card => {
                    const countdownInterval = card.dataset.countdownInterval;
                    if (countdownInterval) {
                        clearInterval(parseInt(countdownInterval));
                    }
                });

                startCountdown();
            });
        </script>
    @endonce
@endpush
