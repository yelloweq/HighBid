<?php

namespace App\Enums;

enum AuctionStatus: string
{
    case active = 'Active';
    case processing = 'Processing';
    case closed = 'Closed';
}