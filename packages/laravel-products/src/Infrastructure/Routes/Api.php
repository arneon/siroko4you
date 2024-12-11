<?php

namespace Arneon\LaravelProducts\Infrastructure\Routes;

use Illuminate\Support\Facades\Route;
use Arneon\LaravelProducts\Infrastructure\Controllers\Controller as Controller;

Route::group([
    'prefix' => 'api/products',
    'middleware' => [
        'api',
        'middleware' => 'auth:sanctum',
    ],
], function () {
    Route::post('', [Controller::class, 'create']);
    Route::get('find-by-id/{id}', [Controller::class, 'find']);
    Route::put('{id}', [Controller::class, 'update']);
    Route::delete('{id}', [Controller::class, 'delete']);
});

Route::group([
    'prefix' => 'web/admin/products',
    'middleware' => [
        'web',
        'middleware' => 'auth:sanctum',
    ],
], function () {
    Route::get('', [Controller::class, 'adminProducts']);
});


Route::group([
    'prefix' => 'web/products',
    'middleware' => [
        'web',
    ],
], function () {
    Route::get('', [Controller::class, 'list']);
});
