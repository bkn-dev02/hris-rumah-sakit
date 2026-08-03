<?php

namespace Modules\Master\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Modules\Master\Contracts\Repositories\DepartmentRepositoryInterface;
use Modules\Master\Repositories\DepartmentRepository;
use Modules\Master\Contracts\Repositories\PositionRepositoryInterface;
use Modules\Master\Repositories\PositionRepository;
use Modules\Master\Contracts\Repositories\EmploymentStatusRepositoryInterface;
use Modules\Master\Repositories\EmploymentStatusRepository;
use Modules\Master\Contracts\Repositories\ShiftRepositoryInterface;
use Modules\Master\Repositories\ShiftRepository;
use Modules\Master\Contracts\Repositories\EmployeeRepositoryInterface;
use Modules\Master\Repositories\EmployeeRepository;
use Modules\Master\Contracts\Repositories\EmployeePlacementRepositoryInterface;
use Modules\Master\Repositories\EmployeePlacementRepository;
use Modules\Master\Contracts\Repositories\EmployeeShiftScheduleRepositoryInterface;
use Modules\Master\Repositories\EmployeeShiftScheduleRepository;
use Modules\Master\Contracts\Services\DepartmentServiceInterface;
use Modules\Master\Services\DepartmentService;
use Modules\Master\Contracts\Services\PositionServiceInterface;
use Modules\Master\Services\PositionService;
use Modules\Master\Contracts\Services\EmploymentStatusServiceInterface;
use Modules\Master\Services\EmploymentStatusService;
use Modules\Master\Contracts\Services\ShiftServiceInterface;
use Modules\Master\Services\ShiftService;
use Modules\Master\Contracts\Services\EmployeeServiceInterface;
use Modules\Master\Services\EmployeeService;
use Modules\Master\Contracts\Services\EmployeePlacementServiceInterface;
use Modules\Master\Services\EmployeePlacementService;
use Modules\Master\Contracts\Services\EmployeeShiftScheduleServiceInterface;
use Modules\Master\Services\EmployeeShiftScheduleService;


class MasterServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Master';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'master';

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

    public function register(): void
    {
        parent::register();

        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(PositionRepositoryInterface::class, PositionRepository::class);
        $this->app->bind(EmploymentStatusRepositoryInterface::class, EmploymentStatusRepository::class);
        $this->app->bind(ShiftRepositoryInterface::class, ShiftRepository::class);
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
        $this->app->bind(EmployeePlacementRepositoryInterface::class, EmployeePlacementRepository::class);
        $this->app->bind(EmployeeShiftScheduleRepositoryInterface::class, EmployeeShiftScheduleRepository::class);
        $this->app->bind(DepartmentServiceInterface::class, DepartmentService::class);
        $this->app->bind(PositionServiceInterface::class, PositionService::class);
        $this->app->bind(EmploymentStatusServiceInterface::class, EmploymentStatusService::class);
        $this->app->bind(ShiftServiceInterface::class, ShiftService::class);
        $this->app->bind(EmployeeServiceInterface::class, EmployeeService::class);
        $this->app->bind(EmployeePlacementServiceInterface::class, EmployeePlacementService::class);
        $this->app->bind(EmployeeShiftScheduleServiceInterface::class, EmployeeShiftScheduleService::class);
    }

    /**
     * Define module schedules.
     * 
     * @param $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
}
