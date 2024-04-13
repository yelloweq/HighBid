@php
    $displayAuctions = $totalAuctions;
    if ($totalAuctions >= 1000) {
        $displayAuctions = floor($totalAuctions / 1000) . 'K+';
    }

    $displayBids = $totalBids;
    if ($totalBids >= 1000) {
        $displayBids = floor($totalBids / 1000) . 'K+';
    }

    $displayUsers = $totalUsers;
    if ($totalUsers >= 1000) {
        $displayUsers = floor($totalUsers / 1000) . 'K+';
    }
@endphp

<x-app-layout>
<section class="text-center mb-12 text-white max-w-11xl mx-auto">
    <h2 class="text-4xl md:text-5xl lg:text-6xl text-transparent font-inter font-semibold text-white p-4">
        Explore <span class="text-transparent bg-gradient-to-r from-blue-accent via-blue-400 to-indigo-700 bg-clip-text"> authentic </span> auctions with just one click
    </h2>
    <p class="text-gray-400 mb-6">
        Our automated system makes it easy to find the best deals on the items you love.
    </p>
    <div class="flex justify-center space-x-4">
        <button class="bg-blue-600 px-6 py-3 rounded-md text-white font-semibold hover:bg-blue-700" hx-get="{{ route('auctions')}}" hx-target="body" hx-trigger="click" hx-push-url="true">
            Explore
        </button>
        <button
        hx-get="{{ route('auction.create')}}" hx-target="body" hx-trigger="click" hx-push-url="true"
            class="bg-transparent px-6 py-3 rounded-md text-white font-semibold border border-blue-600 hover:border-blue-700 hover:text-blue-500">
            Create
        </button>
    </div>
</section>
<section class="flex justify-between items-center mb-12 max-w-11xl mx-auto">
    <div class="flex space-x-8">
        <div class="text-center">
            <h3 class="text-3xl font-bold text-blue-500">
                {{$displayUsers}}
            </h3>
            <p class="text-gray-400">
                Users
            </p>
        </div>
        <div class="text-center">
            <h3 class="text-3xl font-bold text-blue-500">
                {{$displayBids}}
            </h3>
            <p class="text-gray-400">
                Bids
            </p>
        </div>
        <div class="text-center">
            <h3 class="text-3xl font-bold text-blue-500">
                {{$displayAuctions}}
            </h3>
            <p class="text-gray-400">
                Auctions
            </p>
        </div>
    </div>
    <div class="flex items-center">
        <i class="fas fa-certificate text-blue-500 mr-2">
        </i>
        <p class="text-gray-400">
            More active costumer
        </p>
    </div>
</section>
<section class="mb-12 max-w-11xl mx-auto">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-bold text-yellow-400 limited-auction" data-end-time="16/03/2024 00:00:00">
            Limited Auctions
            <span class="text-yellow-400 countdown-timer">
                {{-- countdown here --}}
            </span>
        </h3>
    </div>
    <div class="grid grid-cols-3 gap-4">
        <!-- First NFT -->
        <div class="bg-gray-800 p-4 rounded-lg">
            <img alt="3D Splash Circle Lines NFT artwork" class="rounded-md mb-4" height="300"
                src="https://oaidalleapiprodscus.blob.core.windows.net/private/org-9jdrtrRdidHiQgfFqdpVh9ls/user-G6G09W48dqtVrpPI2JNm5ixu/img-p4x0nzGY8CGxtGc1Ev8HTh59.png?st=2024-03-06T22%3A41%3A47Z&amp;se=2024-03-07T00%3A41%3A47Z&amp;sp=r&amp;sv=2021-08-06&amp;sr=b&amp;rscd=inline&amp;rsct=image/png&amp;skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&amp;sktid=a48cca56-e6da-484e-a814-9c849652bcb3&amp;skt=2024-03-06T17%3A10%3A37Z&amp;ske=2024-03-07T17%3A10%3A37Z&amp;sks=b&amp;skv=2021-08-06&amp;sig=AR5fjaYZ/8LJcEmvg1BM5Soqa8fJYZwLiEgNGN3Kg5k%3D"
                width="300" />
            <h4 class="text-lg font-semibold mb-2">
                3D Splash Circle Lines
            </h4>
            <p class="text-gray-400 text-sm mb-4">
                #1 - StarkLee @starklee
            </p>
            <div class="flex justify-between items-center mb-4">
                <span class="text-blue-500">
                    07:20:30
                </span>
                <span class="text-gray-400">
                    Current bid
                </span>
                <span class="text-white">
                    6.71 ETH
                </span>
            </div>
            <button class="w-full bg-blue-600 py-2 rounded-md text-white font-semibold hover:bg-blue-700">
                Place a Bid
            </button>
        </div>
        <!-- Second NFT -->
        <div class="bg-gray-800 p-4 rounded-lg">
            <img alt="Cylinder shaped podiums NFT artwork" class="rounded-md mb-4" height="300"
                src="https://oaidalleapiprodscus.blob.core.windows.net/private/org-9jdrtrRdidHiQgfFqdpVh9ls/user-G6G09W48dqtVrpPI2JNm5ixu/img-PmF8gdhSQbqSxxAzihowQC57.png?st=2024-03-06T22%3A41%3A47Z&amp;se=2024-03-07T00%3A41%3A47Z&amp;sp=r&amp;sv=2021-08-06&amp;sr=b&amp;rscd=inline&amp;rsct=image/png&amp;skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&amp;sktid=a48cca56-e6da-484e-a814-9c849652bcb3&amp;skt=2024-03-06T21%3A42%3A22Z&amp;ske=2024-03-07T21%3A42%3A22Z&amp;sks=b&amp;skv=2021-08-06&amp;sig=iajccLDJ32X%2BjkPOuR8taXWdNTVngOaBJbgeXRpD96A%3D"
                width="300" />
            <h4 class="text-lg font-semibold mb-2">
                Cylinder shaped podiums
            </h4>
            <p class="text-gray-400 text-sm mb-4">
                #2 - SupremeSorcerer @supremesorcerer
            </p>
            <div class="flex justify-between items-center mb-4">
                <span class="text-blue-500">
                    07:00:20
                </span>
                <span class="text-gray-400">
                    Current bid
                </span>
                <span class="text-white">
                    6.38 ETH
                </span>
            </div>
            <button class="w-full bg-blue-600 py-2 rounded-md text-white font-semibold hover:bg-blue-700">
                Place a Bid
            </button>
        </div>
        <!-- Third NFT -->
        <div class="bg-gray-800 p-4 rounded-lg">
            <img alt="Haunted by Mask NFT artwork" class="rounded-md mb-4" height="300"
                src="https://oaidalleapiprodscus.blob.core.windows.net/private/org-9jdrtrRdidHiQgfFqdpVh9ls/user-G6G09W48dqtVrpPI2JNm5ixu/img-uP8fwKTuYxKa7IOY8iitRhkQ.png?st=2024-03-06T22%3A41%3A48Z&amp;se=2024-03-07T00%3A41%3A48Z&amp;sp=r&amp;sv=2021-08-06&amp;sr=b&amp;rscd=inline&amp;rsct=image/png&amp;skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&amp;sktid=a48cca56-e6da-484e-a814-9c849652bcb3&amp;skt=2024-03-06T14%3A46%3A47Z&amp;ske=2024-03-07T14%3A46%3A47Z&amp;sks=b&amp;skv=2021-08-06&amp;sig=MO6Ys/cZYYRxiYkh8AQszELydjWzN9wfVj7VgLqurEU%3D"
                width="300" />
            <h4 class="text-lg font-semibold mb-2">
                Haunted by Mask
            </h4>
            <p class="text-gray-400 text-sm mb-4">
                #3 - PointBreak @pointbreak
            </p>
            <div class="flex justify-between items-center mb-4">
                <span class="text-blue-500">
                    07:10:20
                </span>
                <span class="text-gray-400">
                    Current bid
                </span>
                <span class="text-white">
                    6.21 ETH
                </span>
            </div>
            <button class="w-full bg-blue-600 py-2 rounded-md text-white font-semibold hover:bg-blue-700">
                Place a Bid
            </button>
        </div>
    </div>
</section>
</x-app-layout>
