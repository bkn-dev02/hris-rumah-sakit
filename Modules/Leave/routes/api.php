<?php

use Illuminate\Support\Facades\Route;
use Modules\Leave\Http\Controllers\Api\LeaveController;

Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {

        Route::prefix('leave')->group(function () {
            Route::get('/leave-types', [LeaveController::class, 'leaveTypes']);
            Route::post('/requests', [LeaveController::class, 'store']);
            Route::get('/requests', [LeaveController::class, 'myRequests']);
            Route::get('/requests/{id}', [LeaveController::class, 'show']);
            Route::post('/requests/{id}/cancel', [LeaveController::class, 'cancel']);
        });
    });
