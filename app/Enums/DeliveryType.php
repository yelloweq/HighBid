<?php

namespace App\Enums;

enum DeliveryType: string
{
    case DELIVERY = 'delivery';
    case COLLECTION = 'collection';
    case BOTH = 'both';
}