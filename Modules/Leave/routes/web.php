<?php

use Illuminate\Support\Facades\Route;
use Modules\Leave\Http\Controllers\Web\LeaveController;
use Modules\Leave\Http\Controllers\Web\LeaveTypeController;
use Modules\Leave\Http\Controllers\Web\EmployeeLeaveQuotaController;

Route::middleware(['auth', 'verified'])
    ->prefix('leave')
    ->as('leave.')
    ->group(function () {
        Route::get('/', [LeaveController::class, 'index'])->name('index');

        Route::middleware('permission:leave-types.manage')->group(function () {
            Route::resource('leave-types', LeaveTypeController::class);
        });

        Route::middleware('permission:leave-quotas.manage')->group(function () {
            Route::put('employees/{employee}/quotas', [EmployeeLeaveQuotaController::class, 'updateForEmployee'])
                ->name('employees.quotas.update');
        });

        Route::get('/{leaveRequest}', [LeaveController::class, 'show'])->name('show');
        Route::post('/{leaveRequest}/decide', [LeaveController::class, 'decide'])
            ->name('decide');
    });
