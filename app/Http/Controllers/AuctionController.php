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
        $auctions = Auction::all();
        return view('auction.auction-view-all', ['auctions' => $auctions]); 
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
            'start_time' => $request->input('start-time'),
            'end_time' => $request->input('end-time'),
        ]);
        
        return view('auction.auction-view', ['auction' => $auction]);
    }

    // /**
    //  * Display the user's auction form.
    //  */
    // public function edit(Request $request): View
    // {
    //     return view('profile.edit', [
    //         'user' => $request->user(),
    //     ]);
    // }

    // /**
    //  * Update the user's auction information.
    //  */
    // public function update(Request $request): RedirectResponse
    // {
    //     $request->user()->fill($request->validated());

    //     if ($request->user()->isDirty('email')) {
    //         $request->user()->email_verified_at = null;
    //     }

    //     $request->user()->save();

    //     return Redirect::route('profile.edit')->with('status', 'profile-updated');
    // }

    // /**
    //  * Delete the user's auction.
    //  */
    // public function destroy(Request $request): RedirectResponse
    // {
    //     $request->validateWithBag('userDeletion', [
    //         'password' => ['required', 'current_password'],
    //     ]);

    //     $user = $request->user();

    //     Auth::logout();

    //     $user->delete();

    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return Redirect::to('/');
    // }
}
