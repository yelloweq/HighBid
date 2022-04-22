<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'customer_id',
        'auction_id',
        'stripe_charge_id',
        'paid_out',
        'fees_collected',
        'refunded',
    ];

    protected $casts = [
        'refunded' => 'boolean',
    ];

    public function auction()
    {
        return $this->hasOne(Auction::class);
    }

    public function customer()
    {
        return $this->hasOne(User::class);
    }
}
