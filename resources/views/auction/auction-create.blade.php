<x-app-layout>
    <div id="content" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    @dd("working")
                    <x-auction-create-form :imageMatchingKey="$imageMatchingKey" :auctionTypes="$auctionTypes"
                        :deliveryTypes="$deliveryTypes" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
