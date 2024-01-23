<?php

namespace App\Enums;

enum DeliveryType: string
{
    case Delivery = 'delivery';
    case Collection = 'collection';
    case Both = 'both';
}