<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UserController1;
use App\Http\Controllers\API\V1\WebhooksLeadsController1;
use App\Http\Controllers\API\V1\CallController1;

Route::prefix('v1')->group(function () {
    Route::prefix('admin')->middleware('token')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('/signin', [UserController1::class, 'signin'])->withoutMiddleware('token');
        });

        Route::prefix('calls')->group(function () {
            Route::get('/', [CallController1::class, 'index']);
            Route::post('/', [CallController1::class, 'create']);
            Route::get('/{call:uuid}', [CallController1::class, 'read']);
            Route::put('/{call:uuid}', [CallController1::class, 'update']);
            Route::delete('/{call:uuid}', [CallController1::class, 'delete']);
        });
    });

    Route::prefix('webhooks')->group(function () {
        Route::prefix('leads')->group(function () {
            Route::post('/', [WebhooksLeadsController1::class, 'index']);
        });
    });
});
