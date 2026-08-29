<?php

use Illuminate\Support\Facades\Route;
use Modules\Schedule\Http\Controllers\Api\ScheduleController;
use Modules\Schedule\Http\Controllers\Api\SpLetterController;

Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::prefix('schedule')->group(function () {
            Route::get('/me', [ScheduleController::class, 'myMonth']);
        });

        Route::prefix('sp-letters')->group(function () {
            Route::get('/', [SpLetterController::class, 'index']);
            Route::get('/unread-count', [SpLetterController::class, 'unreadCount']);
            Route::get('/{spLetter}', [SpLetterController::class, 'show']);
        });
    });
