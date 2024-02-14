<ul class="w-full">
    @foreach ($auctions as $auction)
    <li>
        <x-auction-card :auction=$auction />
    </li>
    @endforeach
    {{ $auctions->links('vendor.pagination.custom') }}
</ul>  
