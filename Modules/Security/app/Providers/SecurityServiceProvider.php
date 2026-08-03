<?php

namespace Modules\Security\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Security\Contracts\Repositories\UserRepositoryInterface;
use Modules\Security\Repositories\UserRepository;
use Modules\Security\Contracts\Repositories\RoleRepositoryInterface;
use Modules\Security\Repositories\RoleRepository;
use Modules\Security\Http\Middleware\EnsurePermission;
use Modules\Security\Contracts\Repositories\LoginHistoryRepositoryInterface;
use Modules\Security\Repositories\LoginHistoryRepository;
use Modules\Security\Contracts\Services\LoginHistoryServiceInterface;
use Modules\Security\Services\LoginHistoryService;
use Modules\Security\Contracts\Repositories\ActivityLogRepositoryInterface;
use Modules\Security\Repositories\ActivityLogRepository;
use Modules\Security\Contracts\Services\ActivityLogServiceInterface;
use Modules\Security\Services\ActivityLogService;
use Modules\Security\Contracts\Repositories\PermissionRepositoryInterface;
use Modules\Security\Repositories\PermissionRepository;
use Modules\Security\Contracts\Services\PermissionServiceInterface;
use Modules\Security\Services\PermissionService;
use Modules\Security\Providers\EventServiceProvider;

class SecurityServiceProvider extends ServiceProvider
{

    public function register(): void
    {

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(LoginHistoryRepositoryInterface::class, LoginHistoryRepository::class);
        $this->app->bind(LoginHistoryServiceInterface::class, LoginHistoryService::class);
        $this->app->bind(ActivityLogRepositoryInterface::class, ActivityLogRepository::class);
        $this->app->bind(ActivityLogServiceInterface::class, ActivityLogService::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);
    }

    public function boot(): void
    {

        Route::aliasMiddleware('permission', EnsurePermission::class);

        $this->loadViewsFrom(
            module_path('Security', 'resources/views'),
            'security'
        );

        $this->loadMigrationsFrom(
            module_path('Security', 'database/migrations')
        );
    }
}
