<?php

namespace App\Models;

use App\Enums\AuctionType;
use App\Enums\DeliveryType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Auction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'auction_id',
        'title',
        'description',
        'features',
        'type',
        'price',
        'delivery_type',
        'winner_id',
        'seller_id',
        'start_time',
        'end_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => AuctionType::class,
        'delivery_type' => DeliveryType::class,
        'start_time' => 'date',
        'end_time' => 'date',
    ];

    /**
     * Define the relationship with the seller.
     *
     * @return BelongsTo
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class,'seller_id','id');
    }

    public function winner(): HasOne
    {
        return $this->hasOne(User::class,'id','winner_id');
    }
}
