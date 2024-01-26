<?php

namespace App\Enums;

enum AuctionType: string
{
    case LASTBID = 'lastbid';
    case TIMELIMIT = 'timelimit';
}