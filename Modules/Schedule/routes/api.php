<?php

use Illuminate\Support\Facades\Route;
use Modules\Schedule\Http\Controllers\Api\ScheduleController;

Route::prefix('schedule')->name('schedule.')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/my-shift', [ScheduleController::class, 'myResolvedShift'])->name('my-shift');
});
