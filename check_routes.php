<?php

use Illuminate\Support\Facades\Route;

foreach (Route::getRoutes() as $r) {
    if (strpos($r->uri(), 'api/dynamic') !== false) {
        echo $r->uri() . ': ' . json_encode($r->middleware()) . PHP_EOL;
    }
}
