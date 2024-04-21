<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'auction_id',
        'user_id',
        'path',
        'image_matching_key',
        'rekognition_labels',
        'flagged',
        'metadata',
    ];

    protected $casts = [
        'flagged' => 'boolean',
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
}
