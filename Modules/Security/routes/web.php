<?php

use Illuminate\Support\Facades\Route;
use Modules\Security\Http\Controllers\Web\SecurityController;
use Modules\Security\Http\Controllers\Web\UserController;
use Modules\Security\Http\Controllers\Web\RoleController;
use Modules\Security\Http\Controllers\Web\PermissionController;
use Modules\Security\Http\Controllers\Web\LoginHistoryController;
use Modules\Security\Http\Controllers\Web\ProfileController;

Route::middleware(['auth', 'verified'])
    ->prefix('profile')
    ->as('profile.')
    ->controller(ProfileController::class)
    ->group(function () {
        Route::get('/', 'show')->name('show');
        Route::put('/password', 'updatePassword')->name('updatePassword');
    });

Route::middleware(['auth', 'verified'])
    ->prefix('security')
    ->as('security.')
    ->group(function () {

        // landing module
        Route::get('/', [SecurityController::class, 'index'])
            ->name('index');

        // user management
        Route::prefix('users')
            ->name('users.')
            ->controller(UserController::class)
            ->group(function () {

                Route::get('/', 'index')->name('index');

                Route::get('/create', 'create')->name('create');

                Route::post('/', 'store')->name('store');

                Route::get('/{slug}', 'show')->name('show');

                Route::get('/{slug}/edit', 'edit')->name('edit');

                Route::put('/{slug}', 'update')->name('update');

                Route::delete('/{slug}', 'destroy')->name('destroy');

                Route::patch('/{slug}/restore', 'restore')->name('restore');

                Route::delete('/{slug}/force-delete', 'forceDelete')->name('forceDelete');
            });

        // roles management
        Route::prefix('roles')
            ->name('roles.')
            ->controller(RoleController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{role}/edit', 'edit')->name('edit');
                Route::put('/{role}', 'update')->name('update');
                Route::delete('/{role}', 'destroy')->name('destroy');
            });

        Route::prefix('permissions')
            ->name('permissions.')
            ->controller(PermissionController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->middleware('permission:permissions.view');
                Route::get('/create', 'create')->name('create')->middleware('permission:permissions.create');
                Route::post('/', 'store')->name('store')->middleware('permission:permissions.create');
                Route::get('/{permission}/edit', 'edit')->name('edit')->middleware('permission:permissions.update');
                Route::put('/{permission}', 'update')->name('update')->middleware('permission:permissions.update');
                Route::delete('/{permission}', 'destroy')->name('destroy')->middleware('permission:permissions.delete');
            });

        Route::get('/login-histories', [LoginHistoryController::class, 'index'])
            ->name('login-histories.index')
            ->middleware('permission:login-histories.view');
    });
