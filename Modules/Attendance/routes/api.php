<?php

use Illuminate\Support\Facades\Route;
use Modules\Attendance\Http\Controllers\Api\AttendanceController;
use Modules\Attendance\Http\Controllers\Api\AttendanceExceptionRequestController;
use Modules\Attendance\Http\Controllers\Api\CheckInController;
use Modules\Attendance\Http\Controllers\Api\EmergencyCheckInController;
use Modules\Attendance\Http\Controllers\Api\AttendanceStatusController;

Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {

        Route::prefix('attendance')->group(function () {
            Route::get('/statuses', [AttendanceStatusController::class, 'index']);
            Route::get('/statuses/leave', [AttendanceStatusController::class, 'leaveStatus']);

            Route::post('/check-in', [CheckInController::class, 'store']);
            Route::get('/location', [AttendanceController::class, 'myLocation']);
            Route::post('/check-out', [AttendanceController::class, 'checkOut']);
            Route::get('/history', [AttendanceController::class, 'history']);
            Route::get('/today', [AttendanceController::class, 'today']);

            Route::post('/exception-requests', [AttendanceExceptionRequestController::class, 'store']);
            Route::get('/exception-requests/history', [AttendanceExceptionRequestController::class, 'history']);
            Route::post('/emergency-check-in', [EmergencyCheckInController::class, 'store']);
            Route::get('/emergency-check-in/today', [EmergencyCheckInController::class, 'today']);
            Route::get('/emergency-check-in/{id}', [EmergencyCheckInController::class, 'show']);
            Route::get('/emergency-check-in/history', [EmergencyCheckInController::class, 'history']);
        });
    });
