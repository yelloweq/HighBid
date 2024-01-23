<?php

namespace App\Models;

use App\Enums\AuctionType;
use App\Enums\DeliveryType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


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
        'delivery_type' => DeliveryType::class
    ];

    public function seller(): HasOne
    {
        return $this->hasOne(User::class,'id','seller_id');
    }

    public function winner(): HasOne
    {
        return $this->hasOne(User::class,'id','winner_id');
    }
}
