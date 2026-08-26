<?php

use Illuminate\Support\Facades\Route;
use Modules\Schedule\Http\Controllers\Web\ScheduleController;

Route::prefix('schedule')->name('schedule.')->middleware(['auth'])->group(function () {
    Route::get('/', [ScheduleController::class, 'index'])
        ->name('index')
        ->middleware('permission:schedule.view');

    Route::post('/', [ScheduleController::class, 'store'])
        ->name('store')
        ->middleware('permission:schedule.manage');
});
