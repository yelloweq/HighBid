<?php

namespace App\Http\Controllers;

use Mauricius\LaravelHtmx\Http\HtmxResponse;

class HtmxController extends Controller
{
    public function remove(): HtmxResponse
    {
        return new HtmxResponse('');
    }
}
