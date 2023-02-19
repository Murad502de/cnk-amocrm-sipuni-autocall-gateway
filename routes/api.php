<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UserController1;
use App\Http\Controllers\API\V1\WebhooksLeadsController1;

Route::prefix('v1')->group(function () {
    Route::prefix('admin')->middleware('token')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('/signin', [UserController1::class, 'signin'])->withoutMiddleware('token');
        });
    });

    Route::prefix('webhooks')->group(function () {
        Route::prefix('leads')->group(function () {
            Route::post('/', [WebhooksLeadsController1::class, 'changeStage']);
        });
    });
});
