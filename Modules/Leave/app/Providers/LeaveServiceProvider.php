<?php

namespace Modules\Leave\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Modules\Leave\Contracts\Repositories\LeaveTypeRepositoryInterface;
use Modules\Leave\Contracts\Repositories\LeaveRequestRepositoryInterface;
use Modules\Leave\Contracts\Services\LeaveRequestServiceInterface;
use Modules\Leave\Repositories\LeaveTypeRepository;
use Modules\Leave\Repositories\LeaveRequestRepository;
use Modules\Leave\Services\LeaveRequestService;
use Modules\Leave\Contracts\Repositories\EmployeeLeaveQuotaRepositoryInterface;
use Modules\Leave\Repositories\EmployeeLeaveQuotaRepository;


class LeaveServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Leave';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'leave';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Define module schedules.
     * 
     * @param $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }

    public function register(): void
    {
        parent::register();

        $this->app->bind(LeaveTypeRepositoryInterface::class, LeaveTypeRepository::class);
        $this->app->bind(LeaveRequestRepositoryInterface::class, LeaveRequestRepository::class);
        $this->app->bind(LeaveRequestServiceInterface::class, LeaveRequestService::class);
        $this->app->bind(EmployeeLeaveQuotaRepositoryInterface::class, EmployeeLeaveQuotaRepository::class);
    }
}
