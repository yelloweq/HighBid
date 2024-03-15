<?php

namespace App\Jobs;

use App\Models\Auction;
use Aws\Exception\AwsException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class ProcessImageWithRekognition implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $auction;

    protected $recognitionClient;

    public function __construct(Auction $auction)
    {
        $this->auction = $auction;
    }

    public function handle()
    {
        try {
            $this->recognitionClient = new RekognitionClient([
                'region' => env('AWS_DEFAULT_REGION'),
                'version' => 'latest',
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ]
            ]);
            Log::info('Client created');
        } catch (AwsException $e) {
            Log::error('Error creating Rekognition client:', [
                'error' => $e->getMessage()
            ]);
        }

        foreach ($this->auction->images as $image) {
            $imagePath = fopen(storage_path('app/public/' . $image->path), 'r');
            $imageBytes = fread($imagePath, filesize(storage_path('app/public/' . $image->path)));

            try {
                $rekognitionResults = $this->recognitionClient->detectModerationLabels([
                    'Image' => [
                        'Bytes' => $imageBytes
                    ],
                    'MaxLabels' => 10,
                    'MinConfidence' => 75
                ]);

                $rekognitionLabels = $rekognitionResults->get('ModerationLabels');

                if (!empty($rekognitionLabels)) {
                    $recognitionLabelString = implode(",", array_column($rekognitionLabels, 'Name'));
                    $image->update(['flagged' => true, 'rekognition_labels' => $recognitionLabelString]);

                    Image::make(storage_path('app/public/' . $image->path))->blur(80)->save();
                }
                Log::info('Image processed with Rekognition:', [
                    'image' => $image->id,
                    'labels' => $rekognitionLabels
                ]);
            } catch (AwsException $e) {
                Log::error('Error processing image with Rekognition:', [
                    'image' => $image->id,
                    'error' => $e->getMessage()
                ]);
            }

            fclose($imagePath);
        }
    }
}
