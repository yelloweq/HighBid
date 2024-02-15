<?php

namespace App\Http\Controllers;

use App\Models\AuctionImage;
use Illuminate\Http\Request;

class AuctionImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->hasFile('file') && !empty($request->imageMatchingKey)){
            try {
                foreach ($request->file('file') as $file) {
                    $imageName = time().'.'. \Illuminate\Support\Str::random(10).'.'.$file->getClientOriginalExtension();
                    $file->move(public_path('images'), $imageName);
                    AuctionImage::create(['path' => $imageName, 'image_matching_key' => $request->imageMatchingKey]);
                }
                return response()->json(['success'=>'Upload complete.']);
            }
            catch (\Exception $e) {
                return response()->json(['error'=>'Upload failed.']);
            }
        }
        else
        {
            return response()->json(['error'=>'Upload failed.']);
        }
    }
}
