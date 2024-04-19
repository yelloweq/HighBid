<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\AuctionImageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HtmxController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\ThreadController;
use App\Http\Middleware\UpdateUserActivity;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/auction/search', [AuctionController::class, 'search'])->name('auction.search');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/auctions', [AuctionController::class, 'view_all'])->name('auctions');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/faq', [FAQController::class, 'index'])->name('faq');
Route::get('/auction/{auction}/watchers', [AuctionController::class, 'getUsersWatching'])->name('auction.watchers');
Route::get('/auction/{auction}/latest_bid', [AuctionController::class, 'latestBid'])->name('auction.latestBid');
Route::get('/auction/limited', [AuctionController::class, 'getLimitedAuctions'])->name('auctions.limited');
Route::get('/auction/{auction}/recent-bids', [AuctionController::class, 'getRecentBidsForAuction'])->name('auction.recentBids');
Route::post('/auction/{auction}', [AuctionController::class, 'bid'])->name('auction.bid');
Route::get('/remove-element', [HtmxController::class, 'remove'])->name('htmx.remove');
Route::get('/forum', [ThreadController::class, 'index'])->name('forum');
Route::get('/forum/thread/create', [ThreadController::class, 'createThread'])->name('thread.create');
Route::post('forum/thread/store', [ThreadController::class, 'storeThread'])->name('thread.store');

Route::get('forum/threads', [ThreadController::class, 'showThreads'])->name('thread.show');

Route::get('forum/thread/{thread}', [ThreadController::class, 'editThread'])->name('thread.edit');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'view'])->name('dashboard');
    Route::post('dashboard/search', [DashboardController::class, 'search'])->name('dashboard.search');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/auction', [AuctionController::class, 'store'])->name('auction.store');
    Route::get('/auction/create', [AuctionController::class, 'create'])->name('auction.create');


    Route::post('/upload/images', [AuctionImageController::class, 'store'])->name('auction.images.store');

    Route::get('/payment/success', [StripeController::class, 'success'])->name('payment.success');
    Route::post('/payment/{auction}', [StripeController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/{auction}', [StripeController::class, 'index'])->name('payment.index');
});

Route::get('/auction/{auction}', [AuctionController::class, 'view'])->name('auction.view')->middleware(UpdateUserActivity::class);



require __DIR__ . '/auth.php';
