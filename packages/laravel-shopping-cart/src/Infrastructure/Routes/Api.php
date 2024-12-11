<?php

namespace Arneon\LaravelShoppingCart\Infrastructure\Routes;

use Illuminate\Support\Facades\Route;
use Arneon\LaravelShoppingCart\Infrastructure\Controllers\Controller as Controller;
use Illuminate\Http\Request;

Route::group([
    'prefix' => 'api/shopping-cart',
    'middleware' => [
        'api',
        'middleware' => 'auth:sanctum',
    ],
], function () {
//    Route::post('', ['before'=>'csrf', Controller::class, 'addItem']);
    Route::post('', [Controller::class, 'addItem']);
    Route::put('{cart_code}/{product_id}', [Controller::class, 'removeItem']);
    Route::put('{cart_code}/{product_id}/{quantity}', [Controller::class, 'updateItemQuantity']);

});

Route::group([
    'prefix' => 'web/shopping-cart',
    'middleware' => [
        'web',
    ],
], function () {
    Route::get('', [Controller::class, 'show']);
    Route::get('checkout/{cart_code}', [Controller::class, 'checkout']);
});
