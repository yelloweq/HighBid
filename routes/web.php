<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Enums\DeliveryType;
use App\Enums\AuctionType;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\AuctionImageController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;

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

Route::get('/', [AuctionController::class, 'view_all'])->name('auctions');
// create controllers for info pages.
Route::get('/about', [AuctionController::class, 'view_all'])->name('about');
Route::get('/faq', [AuctionController::class, 'view_all'])->name('faq');
Route::get('/auction/{auction}', [AuctionController::class, 'view'])->name('auction.view');

Route::post('/auction/search', [AuctionController::class, 'search'])->name('auction.search');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'view'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/auction', [AuctionController::class, 'store'])->name('auction.store');
    Route::get('/auction', [AuctionController::class, 'create'])->name('auction.create');
    Route::post('/auction/{auction}', [AuctionController::class, 'bid'])->name('auction.bid');

    Route::post('/upload/images', [AuctionImageController::class, 'store'])->name('auction.images.store');
    // messages controller needed or package
    Route::get('/messages', [AuctionController::class, 'view_all'])->name('messages');

});



require __DIR__.'/auth.php';
