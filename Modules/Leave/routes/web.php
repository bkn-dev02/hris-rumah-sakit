<?php

use Illuminate\Support\Facades\Route;
use Modules\Leave\Http\Controllers\DashboardLeaveController;
use Modules\Leave\Http\Controllers\Web\LeaveController;
use Modules\Leave\Http\Controllers\Web\LeaveTypeController;
use Modules\Leave\Http\Controllers\Web\EmployeeLeaveQuotaController;

Route::middleware(['auth', 'verified'])
    ->prefix('leave')
    ->as('leave.')
    ->group(function () {
        Route::get('/', [DashboardLeaveController::class, 'index'])->name('index');

        Route::get('/requests', [LeaveController::class, 'index'])->name('requests.index');

        Route::middleware('permission:leave-types.manage')->group(function () {
            Route::resource('leave-types', LeaveTypeController::class);
        });

        Route::middleware('permission:leave-quotas.manage')->group(function () {
            Route::put('employees/{employee}/quotas', [EmployeeLeaveQuotaController::class, 'updateForEmployee'])
                ->name('employees.quotas.update');
        });

        Route::get('/{leaveRequest}', [LeaveController::class, 'show'])->name('show');
        Route::get('/{leaveRequest}/attachment/download', [LeaveController::class, 'downloadAttachment'])
            ->name('attachment.download');
        Route::post('/{leaveRequest}/decide', [LeaveController::class, 'decide'])
            ->name('decide');
    });
