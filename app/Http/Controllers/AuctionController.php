<?php

namespace App\Http\Controllers;

use Akaunting\Money\Money;
use App\Jobs\MatchUploadedImagesToAuction;
use App\Jobs\ProcessImageWithRekognition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use App\Models\Auction;
use App\Http\Requests\CreateAuctionRequest;
use App\Enums\DeliveryType;
use App\Enums\AuctionType;
use App\Jobs\SetAuctionLive;
use App\Models\Category;
use App\Models\Watcher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class AuctionController extends Controller
{
    /**
     * View a single auction page.
     */
    public function view(Auction $auction): View
    {
        $auction->load('bids');
        return view('auction.auction-view', ['auction' => $auction, 'seller' => $auction->seller()->first()]);
    }

    /**
     * View all auctions.
     */
    public function view_all(Request $request): View
    {
        $categories = Category::getCategories();
        $deliveryTypes = DeliveryType::cases();

        $auctions = $this->searchAuctionsFromRequest($request);

        return view('auction.auction-view-all', [
            'auctions' => $auctions,
            'categories' => $categories,
            'deliveryTypes' => $deliveryTypes
        ]);
    }

    /**
     * Search for auctions.
     */
    public function search(Request $request): Response
    {
        $auctions = $this->searchAuctionsFromRequest($request);

        $newPath = route('auctions') . '?' . http_build_query($request->except('_token'));

        return response()->view('components.auction-grid', ['auctions' => $auctions])
            ->header('HX-Push-Url', $newPath);

    }


    /**
     * Display the auction create form.
     */
    public function create(Request $request): View
    {
        $deliveryTypes = DeliveryType::cases();
        $auctionTypes = AuctionType::cases();

        $imageMatchingKey = Uuid::uuid4()->toString();

        return view('auction.auction-create', [
            'deliveryTypes' => $deliveryTypes,
            'auctionTypes' => $auctionTypes,
            'imageMatchingKey' => $imageMatchingKey
        ]);
    }

    /**
     * Store the auction form data.
     */
    public function store(CreateAuctionRequest $request): View
    {
        //need to add multi image upload
        $auction = Auction::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'features' => $request->input('features'),
            'type' => $request->input('auction-type'),
            'price' => ((float) $request->input('price')) * 100,
            'delivery_type' => $request->input('delivery-type'),
            'seller_id' => $request->user()->id ?? Auth::id(),
            'start_time' => Carbon::now(),
            'end_time' => $request->input('end-time'),
        ]);

        if (!empty($request->auctionCreateKey)) {
            Log::info('Dispatching job to match images to auction and process with rekognition');
            MatchUploadedImagesToAuction::withChain([
                new ProcessImageWithRekognition($auction),
                new SetAuctionLive($auction)
            ])->dispatch($auction, $request->auctionCreateKey);
        }

        return view('auction.auction-view', ['auction' => $auction]);
    }

    public function bid(Request $request, Auction $auction): Response
    {
        if ($auction->status !== 'Active' || $auction->end_time < Carbon::now()) {
            return response(view('components.error', ['message' => 'Auction has ended']));
        }
        if ($auction->seller->id == $request->user()->id) {
            return response(view('components.error', ['message' => 'You cannot bid on your own auction']));
        }
    
        $currentHighestBidInPence = $auction->getHighestBid();
        $bidAmountInPence = $request->input('bid') * 100;

        $validator = Validator::make($request->all(), [
            'bid' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
        ]);

        $validator->after(function ($validator) use ($currentHighestBidInPence, $bidAmountInPence) {
            if ($bidAmountInPence <= $currentHighestBidInPence) {
                $validator->errors()->add('bid', 'Your bid must be higher than ' . Money::GBP($currentHighestBidInPence));
            }
        });

        if ($validator->fails()) {
            return response(view('components.error', ['message' => $validator->errors()->first()]));
        }
    
        $bidIncrement = $auction->getBidIncrement();
        $newBidValueWithIncrement = $currentHighestBidInPence + $bidIncrement;
        
        $isAutobid = $request->input('auto_bid') == '1';
    
        $incrementedBidAmount = $isAutobid && ($newBidValueWithIncrement <= $bidAmountInPence)
            ? $newBidValueWithIncrement
            : $bidAmountInPence;
    
        DB::transaction(function () use ($auction, $request, $isAutobid, $bidAmountInPence, $incrementedBidAmount) {
            $auction->bids()->create([
                'user_id' => $request->user()->id,
                'auto_bid' => $isAutobid,
                'amount' => $bidAmountInPence,
                'current_amount' => $incrementedBidAmount,
            ]);
        });
    
        return response(view('components.success', ['message' => "Bid placed successfully"]));
    }


    public function latestBid(Auction $auction): View
    {
        $latestBid = $auction->bids()->latest()->first();

        if ($latestBid != null) {
            return view('components.auction-view-latest-bid', ['value' => $latestBid->amount]);
        }

        return view('components.auction-view-latest-bid', ['value' => $auction->price]);
    }

    public function getUsersWatching(Auction $auction): View
    {
        $watchersCount = Watcher::where('auction_id', $auction->id)->where('last_activity_time', '>', Carbon::now()->subDay())->count();
        return view('components.auction-view-watchers', ['watchersCount' => $watchersCount]);
    }

    protected function searchAuctionsFromRequest(Request $request): LengthAwarePaginator
    {
        $auctionsQuery = Auction::query()
            ->where('status', 'Active')

            ->when($request->search && !empty($request->search), function ($q, $search) {
                return $q->where('title', 'like', "%$search%");
            })
            ->when($request->category && $request->category !== "all", function ($q) use ($request) {
                return $q->whereHas('category', function ($query) use ($request) {
                    $query->where('id', $request->category);
                });
            })
            ->when($request->status, function ($q, $status) {
                return $q->where('status', $status);
            })
            ->when($request->delivery, function ($q, $deliveryType) {
                return $q->where('delivery_type', $deliveryType);
            });
            
            $auctions = $auctionsQuery->paginate($request->input('per_page', 10), ['*'], 'page', $request->query('page', 1)
            )->appends($request->all());

        return $auctions;
    }

    public function getLimitedAuctions(): View
    {
        $auctions = Auction::where('status', 'Active')
            ->where('end_time', '<', Carbon::now()->addDay())
            ->withCount(['bids' => function($query) {
                // Count only bids within the last 24 hours
                $query->where('created_at', '>=', Carbon::now()->subDay());
            }])
            ->having('bids_count', '>', 0)
            ->orderByDesc('bids_count')
            ->limit(3)
            ->get();
        
        return view('components.limited-auctions-grid', ['auctions' => $auctions]);
    }

    public function updateAuctionWithAutoBid(Auction $auction) 
    {
        $highest_autobid = $auction->bids->where('auto_bid', true)->max('amount');
        $highest_bid = $auction->bids->where('auto_bid', false)->max('amount');
        if ($highest_autobid > $highest_bid) {
            $auction->update(['price' => $highest_autobid]);
        }
    }
}
