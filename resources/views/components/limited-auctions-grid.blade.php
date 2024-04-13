@props(['auctions'])

<div class="grid grid-cols-3 gap-4">
    @foreach ($auctions as $auction)
        <x-limited-auctions-grid-item :auction="$auction" />
    @endforeach
</div>
