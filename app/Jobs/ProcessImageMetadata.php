<?php

namespace App\Jobs;

use App\Models\Auction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class ProcessImageMetadata implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Auction $auction)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $images = $this->auction->images;
        $referenceModel = null;
        $referenceLocation = null;

        foreach ($images as $image) {
            $metadata = json_decode($image->metadata, true);

            if (!$referenceModel) {
                $referenceModel = $metadata['Model'] ?? null;
                $referenceLocation = $metadata['GPS'] ?? null;
                continue;
            }

            if (($metadata['Model'] ?? null) !== $referenceModel) {
                Log::warning("Model mismatch for image {$image->id} in auction {$this->auction->id}");
                $image->flagMetadataMismatch('Model');
            }

            if (!$this->locationApproximatelySame($referenceLocation, $metadata['GPS'] ?? null)) {
                Log::warning("Location mismatch for image {$image->id} in auction {$this->auction->id}");
                $image->flagMetadataMismatch('Location');
            }
        }
    }

    /**
     * Compare two GPS locations to determine if they are approximately the same.
     *
     * @param array|null $loc1
     * @param array|null $loc2
     * @return bool
     */
    private function locationApproximatelySame($loc1, $loc2)
    {
        if (!$loc1 || !$loc2) {
            return false;
        }

        // Calculate differences in geographic coordinates
        $latDiff = abs($loc1['Latitude'] - $loc2['Latitude']);
        $longDiff = abs($loc1['Longitude'] - $loc2['Longitude']);

        // Convert degrees to miles (approximation)
        $latMiles = $latDiff * 69;  // 1 degree of latitude ~ 69 miles
        $longMiles = $longDiff * (69 * cos($loc1['Latitude'] * pi() / 180));  // Correcting longitude by average cosine(latitude)

        // is within 30 miles
        return sqrt($latMiles ** 2 + $longMiles ** 2) < 30;
    }
}
