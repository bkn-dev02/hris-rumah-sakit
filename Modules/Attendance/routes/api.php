<?php

use Illuminate\Support\Facades\Route;
use Modules\Attendance\Http\Controllers\Api\AttendanceController;
use Modules\Attendance\Http\Controllers\Api\AttendanceExceptionRequestController;
use Modules\Attendance\Http\Controllers\Api\CheckInController;

Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {

        Route::prefix('attendance')->group(function () {
            Route::post('/check-in', [CheckInController::class, 'store']);
            Route::get('/location', [AttendanceController::class, 'myLocation']);
            Route::get('/history', [AttendanceController::class, 'history']);
            Route::get('/today', [AttendanceController::class, 'today']);
        });
    });
