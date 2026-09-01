<?php

use Illuminate\Support\Facades\Route;
use Modules\Attendance\Http\Controllers\Web\AttendanceController;
use Modules\Attendance\Http\Controllers\Web\AttendanceExceptionRequestController;
use Modules\Attendance\Http\Controllers\Web\AttendanceLocationController;
use Modules\Attendance\Http\Controllers\Web\AttendanceStatusController;
use Modules\Attendance\Http\Controllers\Web\AttendanceDashboardController;
use Modules\Attendance\Http\Controllers\Web\EmergencyCheckInApprovalController;

Route::middleware(['auth', 'verified'])
    ->prefix('attendance')
    ->as('attendance.')
    ->group(function () {

        // landing module
        Route::get('/', [AttendanceDashboardController::class, 'index'])->name('index');

        // Rekap & koreksi absensi
        Route::prefix('records')
            ->name('attendances.')
            ->controller(AttendanceController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{attendance}', 'show')->name('show')->middleware('permission:attendances.view');
                Route::post('/{attendance}/correct', 'correctStatus')->name('correct')->middleware('permission:attendances.correct');
            });

        // Pengajuan exception (Dinas Luar/Tidak Pulang/dst) - approval
        Route::prefix('exception-requests')
            ->name('exception-requests.')
            ->controller(AttendanceExceptionRequestController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{exceptionRequest}', 'show')->name('show')->middleware('permission:attendance-exceptions.view');
                Route::post('/{exceptionRequest}/approve', 'approve')->name('approve')->middleware('permission:attendance-exceptions.approve');
                Route::post('/{exceptionRequest}/reject', 'reject')->name('reject')->middleware('permission:attendance-exceptions.approve');
            });

        // Presensi Darurat - approval HRD
        Route::prefix('emergency')
            ->name('emergency.')
            ->controller(EmergencyCheckInApprovalController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->middleware('permission:emergency-attendance.approve');
                Route::post('/{id}/decide', 'decide')->name('decide')->middleware('permission:emergency-attendance.approve');
                Route::get('/{id}', 'show')->name('show')->middleware('permission:emergency-attendance.approve');
            });

        // Master - Lokasi Absensi
        Route::prefix('locations')
            ->name('locations.')
            ->controller(AttendanceLocationController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create')->middleware('permission:attendance-locations.create');
                Route::post('/', 'store')->name('store')->middleware('permission:attendance-locations.create');
                Route::get('/{location}/edit', 'edit')->name('edit')->middleware('permission:attendance-locations.update');
                Route::put('/{location}', 'update')->name('update')->middleware('permission:attendance-locations.update');
                Route::delete('/{location}', 'destroy')->name('destroy')->middleware('permission:attendance-locations.delete');
            });

        // Master - Status Kehadiran
        Route::prefix('statuses')
            ->name('statuses.')
            ->controller(AttendanceStatusController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create')->middleware('permission:attendance-statuses.create');
                Route::post('/', 'store')->name('store')->middleware('permission:attendance-statuses.create');
                Route::get('/{attendance_status}/edit', 'edit')->name('edit')->middleware('permission:attendance-statuses.update');
                Route::put('/{attendance_status}', 'update')->name('update')->middleware('permission:attendance-statuses.update');
                Route::delete('/{attendance_status}', 'destroy')->name('destroy')->middleware('permission:attendance-statuses.delete');
            });
    });
