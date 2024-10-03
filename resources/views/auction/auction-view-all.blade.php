<x-app-layout>
    <div class="py-6 px-4 sm:px-6 max-w-11xl mx-auto" hx-view-all-content>
        <div class="flex gap-2 sm:flex-col md:flex-row">
            <div class="w-3/12">
                <x-sidebar :categories="$categories" :deliveryTypes="$deliveryTypes"/>
            </div>
            <div class="w-full">
                <x-auction-grid :auctions="$auctions"/>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
document.body.addEventListener('htmx:afterSwap', function(event) {
    if (event.target.hasAttribute('hx-auction-grid')) {
        let grid = document.getElementById('auction-grid');
        let currentPage = parseInt(grid.getAttribute('data-page')) || 1;
        currentPage++;
        grid.setAttribute('data-page', currentPage);
        grid.setAttribute('hx-post', `{{ route('auction.search') }}?page=${currentPage}`);
    }
});

document.querySelector('#sidebar-form').addEventListener('change', function() {
    let grid = document.getElementById('auction-grid');
    grid.setAttribute('data-page', '1');
    grid.setAttribute('hx-post', `{{ route('auction.search') }}?page=1`);
});
</script>
@endpush
