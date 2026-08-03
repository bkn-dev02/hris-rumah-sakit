<?php

use Illuminate\Support\Facades\Route;
use Modules\Shared\Http\Controllers\SharedController;

Route::middleware(['web'])
    ->prefix('shared')
    ->name('shared.')
    ->group(function () {

        Route::get('/blank', [SharedController::class, 'blank'])
            ->name('blank');
    });
