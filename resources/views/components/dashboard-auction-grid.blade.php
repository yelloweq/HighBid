@props(['auctions'])

<div class="grid grid-cols-2 md:grid-cols-3 gap-4" hx-dashboard-auctions-grid>
    @foreach ($auctions as $auction)
        <x-card-with-button :auction=$auction />
    @endforeach
</div>
