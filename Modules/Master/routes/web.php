<?php

use Illuminate\Support\Facades\Route;
use Modules\Master\Http\Controllers\MasterController;
use Modules\Master\Http\Controllers\Web\DepartmentController;
use Modules\Master\Http\Controllers\Web\EmployeeController;
use Modules\Master\Http\Controllers\Web\EmployeePlacementController;
use Modules\Master\Http\Controllers\Web\EmployeeShiftScheduleController;
use Modules\Master\Http\Controllers\Web\EmploymentStatusController;
use Modules\Master\Http\Controllers\Web\PositionController;
use Modules\Master\Http\Controllers\Web\ShiftController;

Route::middleware(['auth', 'verified'])
    ->prefix('master')
    ->as('master.')
    ->group(function () {

        // landing module
        Route::get('/', [MasterController::class, 'index'])->name('index');

        // Department
        Route::prefix('departments')
            ->name('departments.')
            ->controller(DepartmentController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->middleware('permission:departments.view');
                Route::get('/create', 'create')->name('create')->middleware('permission:departments.create');
                Route::post('/', 'store')->name('store')->middleware('permission:departments.create');
                Route::get('/{department}/edit', 'edit')->name('edit')->middleware('permission:departments.update');
                Route::put('/{department}', 'update')->name('update')->middleware('permission:departments.update');
                Route::delete('/{department}', 'destroy')->name('destroy')->middleware('permission:departments.delete');
                Route::get('/tree', 'tree')->name('tree')->middleware('permission:departments.view');
            });

        // Position
        Route::prefix('positions')
            ->name('positions.')
            ->controller(PositionController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->middleware('permission:positions.view');
                Route::get('/create', 'create')->name('create')->middleware('permission:positions.create');
                Route::post('/', 'store')->name('store')->middleware('permission:positions.create');
                Route::get('/{position}/edit', 'edit')->name('edit')->middleware('permission:positions.update');
                Route::put('/{position}', 'update')->name('update')->middleware('permission:positions.update');
                Route::delete('/{position}', 'destroy')->name('destroy')->middleware('permission:positions.delete');
            });

        // Employment Status
        Route::prefix('employment-statuses')
            ->name('employment-statuses.')
            ->controller(EmploymentStatusController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->middleware('permission:employment-statuses.view');
                Route::get('/create', 'create')->name('create')->middleware('permission:employment-statuses.create');
                Route::post('/', 'store')->name('store')->middleware('permission:employment-statuses.create');
                Route::get('/{employment_status}/edit', 'edit')->name('edit')->middleware('permission:employment-statuses.update');
                Route::put('/{employment_status}', 'update')->name('update')->middleware('permission:employment-statuses.update');
                Route::delete('/{employment_status}', 'destroy')->name('destroy')->middleware('permission:employment-statuses.delete');
            });

        // Shift (versioned, tanpa edit/update biasa)
        Route::prefix('shifts')
            ->name('shifts.')
            ->controller(ShiftController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->middleware('permission:shifts.view');
                Route::get('/create', 'create')->name('create')->middleware('permission:shifts.create');
                Route::post('/', 'store')->name('store')->middleware('permission:shifts.create');
                Route::get('/code/{code}/history', 'history')->name('history')->middleware('permission:shifts.view');
                Route::get('/{shift}/new-version', 'editVersion')->name('editVersion')->middleware('permission:shifts.create');
                Route::delete('/{shift}', 'destroy')->name('destroy')->middleware('permission:shifts.delete');
            });

        // Employee (slug-based, soft delete)
        Route::prefix('employees')
            ->name('employees.')
            ->controller(EmployeeController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->middleware('permission:employees.view');
                Route::get('/create', 'create')->name('create')->middleware('permission:employees.create');
                Route::post('/', 'store')->name('store')->middleware('permission:employees.create');
                Route::get('/{employee}', 'show')->name('show')->middleware('permission:employees.view');
                Route::get('/{employee}/edit', 'edit')->name('edit')->middleware('permission:employees.update');
                Route::put('/{employee}', 'update')->name('update')->middleware('permission:employees.update');
                Route::delete('/{employee}', 'destroy')->name('destroy')->middleware('permission:employees.delete');
                Route::patch('/{employee}/attendance-location', 'setAttendanceLocation')->name('setAttendanceLocation')->middleware('permission:employees.update');
                Route::patch('/{employee}/restore', 'restore')->name('restore')->middleware('permission:employees.update');
                Route::delete('/{employee}/force-delete', 'forceDelete')->name('forceDelete')->middleware('permission:employees.delete');

                // nested: riwayat penempatan
                Route::prefix('{employee}/placements')
                    ->name('placements.')
                    ->group(function () {
                        Route::get('/', [EmployeePlacementController::class, 'index'])->name('index')->middleware('permission:employees.view');
                        Route::get('/create', [EmployeePlacementController::class, 'create'])->name('create')->middleware('permission:employees.update');
                        Route::post('/', [EmployeePlacementController::class, 'store'])->name('store')->middleware('permission:employees.update');
                    });

                // nested: riwayat jadwal shift
                Route::prefix('{employee}/shift-schedules')
                    ->name('shift-schedules.')
                    ->group(function () {
                        Route::get('/', [EmployeeShiftScheduleController::class, 'index'])->name('index')->middleware('permission:employees.view');
                        Route::get('/create', [EmployeeShiftScheduleController::class, 'create'])->name('create')->middleware('permission:employees.update');
                        Route::post('/', [EmployeeShiftScheduleController::class, 'store'])->name('store')->middleware('permission:employees.update');
                    });
            });
    });
