<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\AuctionImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Throwable;

class AuctionImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'imageMatchingKey' => 'required',
            'file' => 'required|array',
        ]);
        try {
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
                $metadata = json_encode($this->extractRelevantExifData($image->exif()));
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('Error encoding metadata: ' . json_last_error_msg());
                    $metadata = null;
                }

                AuctionImage::create([
                    'path' => $imagePath,
                    'image_matching_key' => $request->imageMatchingKey,
                    'user_id' => $request->user()->id,
                    'metadata' => $metadata,
                ]);
                $image->save(storage_path('app/public/' . $imagePath));
                Log::info('Image saved to database.');
            }
        } catch (Throwable $e) {
            Log::error('Upload failed. Error: ' . $e->getMessage());
            return response()->json(['error' => 'Upload failed. Please try again later.'], 500);
        }

        return response()->json(['success' => 'uploaded successfully']);
    }

    public function delete(Request $request, Auction $auction, AuctionImage $image)
    {
        if ($auction->user_id !== $request->user()->id) {
            return response()->json(['error' => 'You are not authorized to delete this image.'], 403);
        }

        $image->delete();
        return response()->json(['success' => 'Image deleted successfully']);
    }

    private function extractRelevantExifData($exifRaw)
    {
        $exif = [];
        if (isset($exifRaw['Model'])) {
            $exif['Model'] = mb_convert_encoding($exifRaw['Model'], 'UTF-8', 'auto');
        }

        if (isset(
            $exifRaw['GPSLatitude'],
            $exifRaw['GPSLatitudeRef'],
            $exifRaw['GPSLongitude'],
            $exifRaw['GPSLongitudeRef']
        )) {
            $exif['GPS'] = [
                'Latitude' => $this->processGpsInfo($exifRaw['GPSLatitude'], $exifRaw['GPSLatitudeRef']),
                'Longitude' => $this->processGpsInfo($exifRaw['GPSLongitude'], $exifRaw['GPSLongitudeRef'])
            ];
        }

        return $exif;
    }

    private function processGpsInfo($gpsData, $ref)
    {
        $degrees = (count($gpsData) > 0) ? $this->convertGpsToNumber($gpsData[0]) : 0;
        $minutes = (count($gpsData) > 1) ? $this->convertGpsToNumber($gpsData[1]) : 0;
        $seconds = (count($gpsData) > 2) ? $this->convertGpsToNumber($gpsData[2]) : 0;

        $coordinate = $degrees + ($minutes / 60) + ($seconds / 3600);
        if ($ref == 'S' || $ref == 'W') {
            $coordinate *= -1;
        }
        return $coordinate;
    }

    private function convertGpsToNumber($coordPart)
    {
        $parts = explode('/', $coordPart);
        if (count($parts) == 2) {
            return floatval($parts[0]) / floatval($parts[1]);
        }
        return floatval($coordPart);
    }
}
