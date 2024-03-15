<?php

namespace App\Http\Controllers;

use App\Models\AuctionImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class AuctionImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if (!$request->hasFile('file') || empty($request->imageMatchingKey)) {
                return response()->json(['error' => 'Upload failed. Please try again later.']);
            }
    
            Log::info('imageMatchingKey: ' . $request->imageMatchingKey);
    
            foreach ($request->file('file') as $file) {
                Log::info('foreach image: ' . $file->getClientOriginalName());
                $image = Image::make($file->getRealPath())->resize(null, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                Log::info('foreach image resized');
    
                $imageHash = md5_file($file->getRealPath());
                $imagePath = 'uploads/' . $imageHash . '.' . $file->getClientOriginalExtension();
                Log::info('foreach image path: ' . $imagePath);
    
                $image->save(storage_path('app/public/' . $imagePath));
    
                AuctionImage::create([
                    'path' => $imagePath,
                    'image_matching_key' => $request->imageMatchingKey,
                    'user_id' => $request->user()->id
                ]);
    
                Log::info('Image saved to database.');
            }
    
            return response()->json(['success' => 'uploaded successfully']);
        } catch (\Exception $e) {
            Log::error('Upload failed. Error: ' . $e->getMessage());
            return response()->json(['error' => 'Upload failed. Please try again later.']);
        }
    }
}
