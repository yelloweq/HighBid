<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function view(Request $request)
    {
        $auctions = $request->user()->auctions;

        return view('dashboard', ['auctions' => $auctions]);
    }
}
