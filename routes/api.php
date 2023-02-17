<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('admin')->middleware('token')->group(function () {
        Route::prefix('auth')->group(function () {
            //Route::post('/signin', [AuthController::class, 'signin'])->withoutMiddleware('token');
        });
    });
});
