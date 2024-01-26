<?php

namespace App\Enums;

enum AuctionStatus: string
{
    case PENDING = 'Pending';
    case ACTIVE = 'Active';
    case PROCCESSING = 'Processing';
    case CLOSED = 'Closed';

}