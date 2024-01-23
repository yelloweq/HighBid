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

class AuctionController extends Controller
{

    /**
     * Display the user's auction form.
     */
    public function create(CreateAuctionRequest $request): View
    {
        //fix auth check and display error correctly
        // if (!Auth::check()) {
        //     return view('components.auction-create-fail', [
        //         'errors' => collect(['Authentication error', 'You must be logged in to create an auction.'])
        //     ]);
        // }
        $data = $request->validated();

        if (!$data) {
            return '<h1>ERROR</h1>';
            //return view('components.auction-create-fail');
        }

        $auction = Auction::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'features' => $request->input('features'),
            'type' => $request->input('auction-type'),
            'price' => $request->input('price'),
            'delivery_type' => $request->input('delivery-type'),
            'seller_id' => $request->user() ? $request->user()->id : Auth::id(),
            'start_time' => $request->input('start-time'),
            'end_time' => $request->input('end-time'),
        ]);

        return view('components.auction-create-success');
    }

    /**
     * Display the user's auction form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's auction information.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's auction.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
