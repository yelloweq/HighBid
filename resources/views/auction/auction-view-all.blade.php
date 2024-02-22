<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8" hx-view-all-content>
        <div class="flex ">
            <div class="w-3/12 mr-4">
                <x-sidebar :categories="$categories" :deliveryTypes="$deliveryTypes"/>
            </div>
            <div class="w-full">
                <x-auction-grid :auctions=$auctions/>
            </div>
        </div>
    </div>
</x-app-layout>
