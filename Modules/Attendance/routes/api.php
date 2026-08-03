<?php

use Illuminate\Support\Facades\Route;
use Modules\Attendance\Http\Controllers\Api\AttendanceController;
use Modules\Attendance\Http\Controllers\Api\AttendanceExceptionRequestController;

Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {

        Route::prefix('attendance')->group(function () {
            Route::post('/check-in', [AttendanceController::class, 'checkIn']);
            Route::get('/location', [AttendanceController::class, 'myLocation']);
            Route::post('/check-out', [AttendanceController::class, 'checkOut']);
            Route::get('/today', [AttendanceController::class, 'today']);

            Route::post('/exception-requests', [AttendanceExceptionRequestController::class, 'store']);
            Route::get('/exception-requests/history', [AttendanceExceptionRequestController::class, 'history']);
        });
    });
