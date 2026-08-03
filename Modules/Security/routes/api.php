<?php

use Illuminate\Support\Facades\Route;
use Modules\Security\Http\Controllers\Api\AuthController;
use Modules\Security\Http\Controllers\Api\ProfileController;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::prefix('profile')->controller(ProfileController::class)->group(function () {
            Route::get('/', 'show');
            Route::put('/', 'update');
            Route::put('/password', 'updatePassword');
        });
    });
});
