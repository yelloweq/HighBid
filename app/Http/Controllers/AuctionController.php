<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use App\Models\Auction;
use App\Http\Requests\CreateAuctionRequest;
use App\Enums\DeliveryType;
use App\Enums\AuctionType;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class AuctionController extends Controller
{

    /**
     * View a single auction page.
     */
    public function view(Auction $auction): View
    {
        return view('auction.auction-view', ['auction' => $auction]); 
    }

    /**
     * View all auctions.
     */
    public function view_all(Request $request): View
    {
        $categories = Category::getCategories();
        $deliveryTypes = DeliveryType::cases();

        $auctions = Auction::query()
        ->when($request->search, fn ($q, $search) => 
        $q->where('title', 'like', "%$search%"))
        ->when($request->category, fn ($q, $category) => 
        $q->whereCategoryId($category))
        ->when($request->status == 'Closed', fn ($q) => 
        $q->where('status', 'Closed'), fn ($q) => 
        $q->where('status', 'Active'))
        ->paginate($request->input('per_page', 25))
        ->appends($request->all());
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
        $auctions = Auction::query()
        ->when($request->search, fn ($q, $search) => 
        $q->where('title', 'like', "%$search%"))
        ->when($request->category, fn ($q, $category) => 
        $q->whereCategoryId($category))
        ->when($request->status, fn ($q, $status) => 
        $q->where('status', $status))
        ->when($request->delivery, fn ($q, $deliveryType) => 
        $q->where('delivery_type', $deliveryType))
        ->paginate($request->input('per_page', 25))
        ->appends($request->all());

        $newPath = route('auctions') . '?' . http_build_query($request->all());

        return response()->view('components.auction-grid', ['auctions' => $auctions])
            ->header('HX-Push-Url', $newPath ); 
    }


    /**
     * Display the auction create form.
     */
    public function create(Request $request): View
    {
        $deliveryTypes = DeliveryType::cases();
        $auctionTypes = AuctionType::cases();
        return view('auction.auction-create', [
            'deliveryTypes' => $deliveryTypes,
            'auctionTypes' => $auctionTypes
        ]); 
    }

    /**
     * Store the auction form data.
     */
    public function store(CreateAuctionRequest $request): View
    {
        $auction = Auction::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'features' => $request->input('features'),
            'type' => $request->input('auction-type'),
            'price' => $request->input('price'),
            'delivery_type' => $request->input('delivery-type'),
            'seller_id' => $request->user()->id ?? Auth::id(),
            'start_time' => Carbon::now(),
            'end_time' => $request->input('end-time'),
        ]);
        
        return view('auction.auction-view', ['auction' => $auction]);
    }
}
