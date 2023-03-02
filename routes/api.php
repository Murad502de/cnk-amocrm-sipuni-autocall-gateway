<?php

use App\Http\Controllers\API\V1\CallController1;
use App\Http\Controllers\API\V1\ServicesAmoCrmController1;
use App\Http\Controllers\API\V1\UserController1;
use App\Http\Controllers\API\V1\WebhooksLeadsController1;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\WebhooksSipuniController1;

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

        Route::prefix('sipuni')->group(function () {
            Route::post('/', [WebhooksSipuniController1::class, 'index']);
        });
    });

    Route::prefix('services')->group(function () {
        Route::prefix('amocrm')->group(function () {
            Route::prefix('auth')->group(function () {
                Route::get('signin', [ServicesAmoCrmController1::class, 'signin']);
                Route::get('signout', [ServicesAmoCrmController1::class, 'signout']);
            });
        });
    });
});
