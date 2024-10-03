<?php

return [
    'stripe' => [
        'pk' => env('STRIPE_PUBLIC_KEY'),
        'sk' => env('STRIPE_SECRET_KEY'),
    ],
    //Any other payment gateway
];
