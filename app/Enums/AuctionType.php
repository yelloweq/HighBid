<?php

namespace App\Enums;

enum AuctionType: string
{
    case LastBid = 'last_bid';
    case TimeLimit = 'time_limit';
}