<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'metadata_mismatch_reason',
    ];

    protected $casts = [
        'flagged' => 'boolean',
    ];

    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class);
    }
    public function flagMetadataMismatch(string $field)
    {
        $this->metadata_mismatch_reason = $field;
        $this->save();
    }

    public function getMismatchReason()
    {
        return $this->whereNotNull('metadata_mismatch_reason')->first()->metadata_mismatch_reason;
    }
}
