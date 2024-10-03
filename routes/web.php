<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\AuctionImageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HtmxController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\ThreadController;
use App\Http\Middleware\UpdateUserActivity;


Route::post('/auction/search', [AuctionController::class, 'search'])->name('auction.search');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/auctions', [AuctionController::class, 'view_all'])->name('auctions');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/faq', [FAQController::class, 'index'])->name('faq');
Route::get('/forum', [ThreadController::class, 'index'])->name('forum');

Route::get('/remove-element', [HtmxController::class, 'remove'])->name('htmx.remove');

Route::get('/forum/thread/create', [ThreadController::class, 'createThread'])->name('thread.create');
Route::get('forum/thread/{thread}/comments', [ThreadController::class, 'getThreadComments'])->name('thread.comments');
Route::get('forum/thread/{thread}/edit', [ThreadController::class, 'editThread'])->name('thread.edit');
Route::get('forum/thread/{thread}', [ThreadController::class, 'viewThread'])->name('thread.view');
Route::get('forum/threads', [ThreadController::class, 'showThreads'])->name('thread.show');

Route::get('thread/tags', [ThreadController::class, 'getThreadTagsList'])->name('thread.tags');
Route::post('updateRating/{type}/{model}', [RatingController::class, 'createOrUpdate'])->name('rating.createOrUpdate');

Route::get('payment', [SellerController::class, 'save'])->name('save.express');

Route::middleware('auth')->group(function () {
    Route::middleware('hasSellerPaymentAccount')->group(function () {
        Route::get('/auction/create', [AuctionController::class, 'create'])->name('auction.create');
        Route::get('/payment/login', [SellerController::class, 'login'])->name('stripe.login');
        Route::post('/auction', [AuctionController::class, 'store'])->name('auction.store');
    });

    Route::middleware('hasCustomerPaymentAccount')->group(function () {
        Route::post('/auction/{auction}', [AuctionController::class, 'bid'])->name('auction.bid');
    });

    Route::group(['prefix' => 'payment'], function () {
        Route::get('/express/login', [SellerController::class, 'login'])->name('login.express');
        Route::get('/express', [SellerController::class, 'create'])->name('create.express');
        Route::get('/{auction}/success', [StripeController::class, 'success'])->name('payment.success');

        Route::get('/card', [CustomerController::class, 'form'])->name('stripe.form');
        Route::post('/card', [CustomerController::class, 'save'])->name('save.customer');

        Route::get('/{auction}', [StripeController::class, 'index'])->name('payment.index');
        Route::post('/{auction}', [StripeController::class, 'checkout'])->name('payment.checkout');


    });

    Route::post('dashboard/search', [DashboardController::class, 'search'])->name('dashboard.search');
    Route::get('/dashboard', [DashboardController::class, 'view'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::post('forum/thread/store', [ThreadController::class, 'storeThread'])->name('thread.store');

    Route::post('/upload/images', [AuctionImageController::class, 'store'])->name('auction.images.store');

    Route::delete('auction/{auction}/images/{image}/delete', [AuctionImageController::class, 'destroy'])->name('auction.images.delete');
    Route::get('auction/{auction}/edit/form', [AuctionController::class, 'edit'])->name('auction.edit.form');
    Route::post('auction/{auction}/update', [AuctionController::class, 'update'])->name('auction.update');

});

Route::group(['prefix' => '/auction/'], function () {
    Route::get('limited', [AuctionController::class, 'getLimitedAuctions'])->name('auctions.limited');
    Route::get('{auction}/watchers', [AuctionController::class, 'getUsersWatching'])->name('auction.watchers');
    Route::get('{auction}/latest_bid', [AuctionController::class, 'latestBid'])->name('auction.latestBid');
    Route::get('{auction}/recent-bids', [AuctionController::class, 'getRecentBidsForAuction'])->name('auction.recentBids');
    Route::get('{auction}', [AuctionController::class, 'view'])->name('auction.view')->middleware(UpdateUserActivity::class);
});

require __DIR__ . '/auth.php';
