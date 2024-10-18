<?php

namespace App\Models;

use App\Enums\AuctionType;
use App\Enums\DeliveryType;
use App\Helpers\BidIncrementHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Auction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'features',
        'type',
        'status',
        'price',
        'delivery_type',
        'winner_id',
        'seller_id',
        'start_time',
        'end_time',
        'end_auction_job_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => AuctionType::class,
        'delivery_type' => DeliveryType::class,
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Define the relationship with the seller.
     *
     * @return BelongsTo
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }

    public function winner(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'winner_id');
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(AuctionImage::class);
    }

    public function watchers(): HasMany
    {
        return $this->hasMany(Watcher::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    //TODO: remove if not used
    public function timeRemaining(): string
    {
        if ($this->end_time->greaterThan(Carbon::now())) {
            $interval = Carbon::now()->diff($this->end_time);
            if ($interval->days <= 0) {
                return $interval->format('%h hours, %i minutes, %s seconds');
            }
            return '';
        } else {
            return 'Ended';
        }
    }

    public function getCurrentHighestBid(): ?Bid
    {
        return $this->bids()->orderByDesc('current_amount')->first();
    }

    public function getCurrentHighestBidder(): ?User
    {
        return $this->bids()->where('current_amount', $this->getCurrentHighestBid()?->current_amount)->first()?->user;
    }

    public function getHighestBid(): ?Bid
    {
        return $this->bids()->orderByDesc('amount')->first();
    }

    public function getHighestBidder(): ?User
    {
        return $this->getHighestBid()?->user;
    }

    public function getBidIncrement(): int
    {
        return BidIncrementHelper::getBidIncrement($this->getCurrentHighestBid()?->current_amount ?: $this->price);
    }
}
