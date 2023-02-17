<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UserController1;

Route::prefix('v1')->group(function () {
    Route::prefix('admin')->middleware('token')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('/signin', [UserController1::class, 'signin'])->withoutMiddleware('token');
        });
    });
});
