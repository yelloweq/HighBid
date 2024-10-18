<?php

namespace App\Http\Controllers;

use Akaunting\Money\Money;
use App\Jobs\EndAuction;
use App\Jobs\MatchUploadedImagesToAuction;
use App\Jobs\ProcessImageWithRekognition;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use App\Models\Auction;
use App\Http\Requests\CreateAuctionRequest;
use App\Enums\DeliveryType;
use App\Enums\AuctionType;
use App\Jobs\IncrementBidsForAuction;
use App\Jobs\ProcessImageMetadata;
use App\Jobs\SetAuctionLive;
use App\Models\Category;
use App\Models\Watcher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRefresh;
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
    public function create(Request $request): View|HtmxResponseClientRedirect
    {
        //TODO: this should already be handled by middleware, remove after checking
        if (!Auth::check()) {
            return new HtmxResponseClientRedirect(route('login'));
        }

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
    public function store(CreateAuctionRequest $request): HtmxResponseClientRedirect
    {
        try {
            $auction = Auction::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'features' => $request->input('features'),
                'type' => $request->input('auction-type'),
                'price' => ((float) $request->input('price')) * 100,
                'delivery_type' => $request->input('delivery-type'),
                'seller_id' => $request->user()->id ?? Auth::id(),
                'start_time' => Carbon::now(),
                'end_time' => Carbon::parse($request->input('end-date')),
            ]);

            if (!empty($request->auctionCreateKey)) {
                Log::info('Dispatching job to match images to auction and process with rekognition');
                //This is chained so that we have scanned for inappropriate images before setting the auction live.
                MatchUploadedImagesToAuction::withChain([
                    new ProcessImageWithRekognition($auction),
                    new ProcessImageMetadata($auction),
                    new SetAuctionLive($auction),
                ])->dispatch($auction, $request->auctionCreateKey);
            }

            $jobUuid = Uuid::uuid4()->toString();

            EndAuction::dispatch($auction, $jobUuid)->delay($auction->end_time);

            /**
             * if auction end time is edited, we create new delayed end auction job with newly generated uuid
             * this is a hack since you cannot get job id before the job is actually running.
             */
            $auction->end_auction_job_id = $jobUuid;
            $auction->save();

            return new HtmxResponseClientRedirect(route('auction.view', ['auction' => $auction->id]));
        } catch (Exception $e) {
            //TODO: Redirect with errors
            return new HtmxResponseClientRedirect(route('home'));
        }
    }

    public function edit(Request $request, Auction $auction): View
    {
        $auctionTypes = AuctionType::cases();
        $deliveryTypes = DeliveryType::cases();
        return view('auction.auction-edit', compact('auction', 'auctionTypes', 'deliveryTypes'));
    }

    public function update(Request $request, Auction $auction)
    {
        //
    }
    public function bid(Request $request, Auction $auction): Response
    {
        //TODO: move below logic to a custom request
        $isAuthenticated = Auth::check();
        $isNotActive = $auction->status !== 'Active';
        $hasEnded = $auction->end_time < Carbon::now();
        $isSeller = $auction->seller->id == $request->user()?->id;

        if (!$isAuthenticated) {
            return response(view('components.message', [
                'message' => 'You need to be logged in to bid on an auction',
                'type' => 'error',
            ]));
        }
        if ($isNotActive || $hasEnded) {
            return response(view('components.message', [
                'message' => 'Auction has ended',
                'type' => 'error',
            ]));
        }
        if ($isSeller) {
            return response(view('components.message', [
                'message' => 'You cannot bid on your own auction',
                'type' => 'error',
            ]));
        }

        $bidAmountInPence = $request->input('bid') * 100;
        $currentHighestBidInPence = $auction->getCurrentHighestBid()->current_amount ?? $auction->price;

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
            return response(view('components.message', [
                'message' => $validator->errors()->first(),
                'type' => 'error',
            ]));
        }

        //TODO: this should just be a bool, why is this a string?
        $isAutobid = $request->input('auto_bid') == '1';
        $isCurrentHighestBidder = $auction->getCurrentHighestBidder() && $auction->getCurrentHighestBidder()->id == $request->user()->id;

        if ($isCurrentHighestBidder) {
            $currentHighestBid = $auction->getCurrentHighestBid();

            if ($currentHighestBid->auto_bid) {
                $currentHighestBid->update([
                    'amount' => $bidAmountInPence,
                ]);
            } else {
                $currentHighestBid->update([
                    'amount' => $bidAmountInPence,
                    'current_amount' => $bidAmountInPence,
                ]);
            }

            IncrementBidsForAuction::dispatch($auction);

            return response(view('components.message', [
                'message' => 'Successfully updated your bid',
                'type' => 'success',
            ]));
        }

        $bidIncrement = $auction->getBidIncrement();
        $newBidValueWithIncrement = $currentHighestBidInPence + $bidIncrement;

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

        IncrementBidsForAuction::dispatch($auction);

        return response(view('components.message', [
            'message' => "Bid placed successfully",
            'type' => 'success',
        ]));
    }


    public function latestBid(Auction $auction): View
    {
        $latestBid = $auction->getCurrentHighestBid()->current_amount ?? $auction->price;

        return view('components.auction-view-latest-bid', ['value' => $latestBid]);
    }

    public function getRecentBidsForAuction(Auction $auction): View
    {
        $recentBids = $auction->bids()->orderByDesc('current_amount')->take(10)->get();
        return view('partials.recent-bids', ['bids' => $recentBids]);
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

        $auctions = $auctionsQuery->paginate(
            $request->input('per_page', 10),
            ['*'],
            'page',
            $request->query('page', 1)
        )->appends($request->all());

        return $auctions;
    }

    public function getLimitedAuctions(): View
    {
        $auctions = Auction::where('status', 'Active')
            ->where('end_time', '<', Carbon::now()->timezone('Europe/london')->addDay())
            ->withCount(['bids' => function ($query) {
                $query->where('updated_at', '>=', Carbon::now()->subDay());
            }])
            ->having('bids_count', '>', 0)
            ->orderByDesc('bids_count')
            ->limit(3)
            ->get();

        return view('components.limited-auctions-grid', ['auctions' => $auctions]);
    }
}
