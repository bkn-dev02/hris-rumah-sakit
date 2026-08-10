<?php

use Illuminate\Support\Facades\Route;
use Modules\Leave\Http\Controllers\LeaveController;

Route::middleware(['auth', 'verified'])
    ->prefix('leaves')
    ->as('leaves.')
    ->group(function () {
        Route::get('/', [LeaveController::class, 'index'])->name('index');
    });
