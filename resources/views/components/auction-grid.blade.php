<div class="p-6 text-gray-900 dark:text-gray-100 flex flex-wrap flex-grow-0 justify-start align-middle gap-3">
    @foreach ($auctions as $auction)
        <x-auction-card :auction=$auction />
    @endforeach
</div>  