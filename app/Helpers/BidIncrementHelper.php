<?php

namespace App\Helpers;

class BidIncrementHelper
{
    protected static function getIncrementRanges()
    {
        return [
            99 => 1,         // Increment by 1p for bids 1p-99p
            499 => 20,       // Increment by 20p for bids £1-£4.99
            1499 => 50,      // Increment by 50p for bids £5-£14.99
            5999 => 100,     // Increment by £1 for bids £15-£59.99
            14999 => 200,    // Increment by £2 for bids £60-£149.99
            29999 => 500,    // Increment by £5 for bids £150-£299.99
            59999 => 1000,   // Increment by £10 for bids £300-£599.99
            149999 => 2000,  // Increment by £20 for bids £600-£1499.99
            299999 => 5000,  // Increment by £50 for bids £1499.99-£2999.99
            300000 => 10000, // Increment by £100 for bids £1500 and above
        ];
    }

    public static function getBidIncrement($highestBid)
    {
        $ranges = self::getIncrementRanges();

        foreach ($ranges as $limit => $increment) {
            if ($highestBid <= $limit) {
                return $increment;
            }
        }

        return end($ranges);
    }
}
