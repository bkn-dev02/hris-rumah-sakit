<?php

use Illuminate\Support\Facades\Route;
use Modules\Leave\Http\Controllers\Web\LeaveController;
use Modules\Leave\Http\Controllers\Web\LeaveTypeController;
use Modules\Leave\Http\Controllers\Web\EmployeeLeaveQuotaController;
use Modules\Leave\Http\Controllers\Web\LeaveApprovalController;

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

        Route::middleware('permission:leave-requests.approve-supervisor')->group(function () {
            Route::get('approvals/supervisor', [LeaveApprovalController::class, 'supervisorIndex'])->name('approvals.supervisor');
            Route::post('{leaveRequestId}/decide-supervisor', [LeaveApprovalController::class, 'decideBySupervisor'])->name('decide-supervisor');
        });

        Route::middleware('permission:leave-requests.approve-hr')->group(function () {
            Route::get('approvals/hr', [LeaveApprovalController::class, 'hrIndex'])->name('approvals.hr');
            Route::post('{leaveRequestId}/decide-hr', [LeaveApprovalController::class, 'decideByHr'])->name('decide-hr');
        });
    });
