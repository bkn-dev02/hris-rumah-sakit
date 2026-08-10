<?php

namespace Modules\Leave\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Leave\Contracts\Repositories\LeaveRequestRepositoryInterface;
use Modules\Leave\Contracts\Repositories\LeaveTypeRepositoryInterface;
use Modules\Leave\Contracts\Services\LeaveRequestServiceInterface;
use Modules\Leave\Repositories\LeaveRequestRepository;
use Modules\Leave\Repositories\LeaveTypeRepository;
use Modules\Leave\Services\LeaveRequestService;

class LeaveServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'leave');
    }

    public function register(): void
    {
        $this->app->bind(LeaveRequestRepositoryInterface::class, LeaveRequestRepository::class);
        $this->app->bind(LeaveTypeRepositoryInterface::class, LeaveTypeRepository::class);
        $this->app->bind(LeaveRequestServiceInterface::class, LeaveRequestService::class);
    }
}
