<?php

namespace Arneon\LaravelCategories\Infrastructure\Routes;

use Illuminate\Support\Facades\Route;
use Arneon\LaravelCategories\Infrastructure\Controllers\Controller as Controller;

Route::group([
    'prefix' => 'api/categories',
    'middleware' => [
        'api',
        'middleware' => 'auth:sanctum',
    ],
], function () {
    Route::get('', [Controller::class, 'list']);
    Route::post('', [Controller::class, 'create']);
    Route::get('find-by-id/{id}', [Controller::class, 'find']);
    Route::put('{id}', [Controller::class, 'update']);
    Route::delete('{id}', [Controller::class, 'delete']);
});

Route::group([
    'prefix' => 'web/admin/categories',
    'middleware' => [
        'web',
        'middleware' => 'auth:sanctum',
    ],
], function () {
    Route::get('', [Controller::class, 'adminCategories']);
});
