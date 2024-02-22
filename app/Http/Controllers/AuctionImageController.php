<?php

namespace App\Http\Controllers;

use App\Models\AuctionImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AuctionImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->hasFile('file') || empty($request->imageMatchingKey)) {
            return response()->json(['error' => 'Upload failed. Please try again later.']);
        }

        $unusedUserImages = AuctionImage::where('user_id', $request->user()->id)
            ->whereNull('auction_id')
            ->get();
        
        
        if ($unusedUserImages->count() > config('imageConfig.max_usused_user_images', 10)) {
            $unusedUserImages->each->delete();
        }

        try {
            foreach ($request->file('file') as $file) {
                // $imagePath = $file->storePublicly('uploads', 'public');
                $image = Image::make($file->getRealPath())->resize(null, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $imageHash = md5($image->__toString());
                $imagePath = 'uploads/' . $imageHash . '.' . $file->getClientOriginalExtension();

                $image->save(storage_path('app/public/' . $imagePath));

                AuctionImage::create([
                    'path' => $imagePath,
                    'image_matching_key' => $request->imageMatchingKey,
                    'user_id' => $request->user()->id
                ]);
            }
            return response()->json(['success' => 'uploaded successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Upload failed. Please try again later. ' . $e->getMessage()]);
        }
    }
}
