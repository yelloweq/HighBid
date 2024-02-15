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
        $auctions = $request->user()->auctions()->paginate($request->input('per_page', 25))
        ->appends($request->all());

        return view('dashboard', ['auctions' => $auctions]);
    }
}
