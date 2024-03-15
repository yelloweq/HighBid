<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        $totalAuctions = Auction::count();
        $totalBids = Bid::count();
        $totalUsers = User::count();

        return view('home', compact('totalAuctions', 'totalBids', 'totalUsers'));
    }
}
