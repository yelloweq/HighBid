<ul class="w-full" hx-auction-grid>
    @foreach ($auctions as $auction)
    <li>
        <x-auction-card :auction=$auction />
    </li>
    @endforeach
    {{ $auctions->links('vendor.pagination.custom') }}
</ul>  
