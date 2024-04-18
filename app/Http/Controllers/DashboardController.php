<?php

namespace App\Http\Controllers;

use App\Enums\AuctionStatus;
use App\Models\Auction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function view(Request $request): View
    {
        $auctions = $request->user()->auctions()->where('status', AuctionStatus::ACTIVE)->orderBy('created_at', 'desc')->paginate($request->input('per_page', 25))
            ->appends($request->all());

        return view('dashboard', ['auctions' => $auctions]);
    }

    /**
     * Search for auctions.
     */
    public function search(Request $request): Response
    {
        $auctions = $this->searchAuctionsFromRequest($request);

        $newPath = route('dashboard') . '?' . http_build_query($request->except('_token'));

        $response = response()->view('components.dashboard-auction-grid', ['auctions' => $auctions]);

        $response->header('HX-Push-Url', $newPath);

        return $response;
    }

    protected function searchAuctionsFromRequest(Request $request): LengthAwarePaginator
    {
        $auctionsQuery = Auction::query()
            ->when($request->search && !empty($request->search), function ($q, $search) use ($request) {
                return $q->where('title', 'like', "%$search%")->where('seller_id', $request->user()->id);
            })
            ->when($request->sort && $request->sort == "active", function ($q) use ($request) {
                return $q->where('status', AuctionStatus::ACTIVE)->where('seller_id', $request->user()->id);
            })
            ->when($request->sort && $request->sort == "inactive", function ($q) use ($request) {
                return $q->where('status', '!=', AuctionStatus::ACTIVE)->where('seller_id', $request->user()->id);
            })
            ->when($request->sort && $request->sort == "won", function ($q) use ($request) {
                return $q->where('winner_id', $request->user()->id);
            })
            ->when($request->sort && $request->sort == "bids", function ($q) use ($request) {
                return $q->whereHas('bids', function ($query) use ($request) {
                    $query->where('user_id', $request->user()->id);
                });
            })->orderBy('created_at', 'desc');

        $auctions = $auctionsQuery->paginate($request->input('per_page', 25), ['*'], 'page', $request->query('page', 1))
            ->appends($request->all());


        return $auctions;
    }
}
