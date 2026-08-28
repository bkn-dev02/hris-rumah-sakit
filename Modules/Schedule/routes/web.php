<?php

use Illuminate\Support\Facades\Route;
use Modules\Schedule\Http\Controllers\Web\ScheduleController;
use Modules\Schedule\Http\Controllers\Web\SpCandidateController;
use Modules\Schedule\Http\Controllers\Web\SpLetterController;

Route::prefix('schedule')->name('schedule.')->middleware(['auth'])->group(function () {
    Route::get('/', [ScheduleController::class, 'index'])
        ->name('index')
        ->middleware('permission:schedule.view');

    Route::post('/', [ScheduleController::class, 'store'])
        ->name('store')
        ->middleware('permission:schedule.manage');

    Route::prefix('sp-candidates')->name('sp-candidates.')->group(function () {
        Route::get('/', [SpCandidateController::class, 'index'])
            ->name('index')
            ->middleware('permission:sp-candidates.view');

        Route::get('/{spCandidate}', [SpCandidateController::class, 'show'])
            ->name('show')
            ->middleware('permission:sp-candidates.view');

        Route::post('/{spCandidate}/confirm', [SpCandidateController::class, 'confirm'])
            ->name('confirm')
            ->middleware('permission:sp-candidates.confirm');

        Route::post('/{spCandidate}/decide', [SpCandidateController::class, 'decide'])
            ->name('decide')
            ->middleware('permission:sp-letters.issue');

        Route::post('/{spCandidate}/issue', [SpLetterController::class, 'store'])
            ->name('issue')
            ->middleware('permission:sp-letters.issue');
    });
});
