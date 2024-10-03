<ul class="w-full" hx-auction-grid
    hx-disinherit="*"
    @if ($auctions->hasMorePages()) hx-post={{ route('auction.search', ['page' => $auctions->currentPage() + 1]) }} hx-trigger="intersect
    once settle:0" hx-swap="afterend"
hx-include="#sidebar-form"> @endif
@foreach ($auctions as $auction)
    <li>
        <x-auction-card :auction="$auction"/>
    </li>
@endforeach
    </ul>

    @push('scripts')
        @once
            <script>
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

                        let displayString = "";
                        if (days > 0) {
                            displayString += days + "d ";
                        }
                        if (days > 0 || hours > 0) {
                            displayString += hours + "h ";
                        }
                        if (days > 0 || hours > 0 || minutes > 0) {
                            displayString += minutes + "m ";
                        }
                        if (days > 0 || hours > 0 || minutes > 0 || seconds > 0) {
                            displayString += seconds + "s ";
                        } else {
                            displayString = "0s ";
                        }

                        countdownElement.innerHTML = displayString;

                    }
                }

                function startCountdown() {
                    const auctionCards = document.querySelectorAll('.auction-card');

                    auctionCards.forEach(card => {
                        const endTime = new Date(card.dataset.endTime).getTime() ?? new Date.now();
                        const countdownElement = card.querySelector('.countdown');

                        updateCountdown(endTime, countdownElement);

                        setInterval(() => {
                            updateCountdown(endTime, countdownElement);
                        }, 1000);
                    });
                }

                document.addEventListener('DOMContentLoaded', startCountdown);
                document.addEventListener('htmx:afterSwap', function (event) {
                    let auctionCards = document.querySelectorAll('.auction-card');
                    auctionCards.forEach(card => {
                        let countdownInterval = card.dataset.countdownInterval;
                        if (countdownInterval) {
                            clearInterval(parseInt(countdownInterval));
                        }
                    });

                    startCountdown();
                });
            </script>
        @endonce
    @endpush
