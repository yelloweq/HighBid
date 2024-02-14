<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex ">
            <div class="w-3/12 bg-blue-gray-50 h-full">Categories here</div>
            <x-auction-grid :auctions=$auctions/>
        </div>
        
    </div>
</x-app-layout>
