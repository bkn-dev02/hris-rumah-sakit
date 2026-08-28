<?php

use Illuminate\Support\Facades\Route;
use Modules\Schedule\Http\Controllers\Api\ScheduleController;

Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::prefix('schedule')->group(function () {
            Route::get('/me', [ScheduleController::class, 'myMonth']);
        });
    });
