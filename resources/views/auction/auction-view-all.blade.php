<x-app-layout>
    <div class="py-6 px-4 sm:px-6 max-w-11xl mx-auto" hx-view-all-content>
        <div class="flex gap-2 sm:flex-col md:flex-row">
            <div class="w-3/12">
                <x-sidebar :categories="$categories" :deliveryTypes="$deliveryTypes"/>
            </div>
            <div class="w-full">
                <x-auction-grid :auctions=$auctions/>
            </div>
        </div>
    </div>
</x-app-layout>
