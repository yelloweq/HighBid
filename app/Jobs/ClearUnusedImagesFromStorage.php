<?php

namespace App\Jobs;

use App\Models\AuctionImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ClearUnusedImagesFromStorage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //delete AuctionImage where auction_id is null and created_at is older than 24 hours
        AuctionImage::whereNull('auction_id')
            ->where('created_at', '<', now()->subMinutes(30))
            ->delete();
    }
}
