<?php

namespace Modules\Attendance\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Modules\Attendance\Contracts\Repositories\AttendanceLocationRepositoryInterface;
use Modules\Attendance\Repositories\AttendanceLocationRepository;
use Modules\Attendance\Contracts\Repositories\AttendanceStatusRepositoryInterface;
use Modules\Attendance\Repositories\AttendanceStatusRepository;
use Modules\Attendance\Contracts\Repositories\AttendanceRepositoryInterface;
use Modules\Attendance\Repositories\AttendanceRepository;
use Modules\Attendance\Contracts\Repositories\AttendanceExceptionRequestRepositoryInterface;
use Modules\Attendance\Repositories\AttendanceExceptionRequestRepository;
use Modules\Attendance\Contracts\Repositories\AttendanceCorrectionRepositoryInterface;
use Modules\Attendance\Repositories\AttendanceCorrectionRepository;
use Modules\Attendance\Contracts\Services\AttendanceLocationServiceInterface;
use Modules\Attendance\Services\AttendanceLocationService;
use Modules\Attendance\Contracts\Services\AttendanceStatusServiceInterface;
use Modules\Attendance\Services\AttendanceStatusService;
use Modules\Attendance\Contracts\Services\AttendanceServiceInterface;
use Modules\Attendance\Services\AttendanceService;
use Modules\Attendance\Contracts\Services\AttendanceExceptionRequestServiceInterface;
use Modules\Attendance\Services\AttendanceExceptionRequestService;
use Modules\Attendance\Contracts\Services\AttendanceCorrectionServiceInterface;
use Modules\Attendance\Services\AttendanceCorrectionService;

class AttendanceServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Attendance';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'attendance';

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

        $this->app->bind(AttendanceLocationRepositoryInterface::class, AttendanceLocationRepository::class);
        $this->app->bind(AttendanceStatusRepositoryInterface::class, AttendanceStatusRepository::class);
        $this->app->bind(AttendanceRepositoryInterface::class, AttendanceRepository::class);
        $this->app->bind(AttendanceExceptionRequestRepositoryInterface::class, AttendanceExceptionRequestRepository::class);
        $this->app->bind(AttendanceCorrectionRepositoryInterface::class, AttendanceCorrectionRepository::class);

        $this->app->bind(AttendanceLocationServiceInterface::class, AttendanceLocationService::class);
        $this->app->bind(AttendanceStatusServiceInterface::class, AttendanceStatusService::class);
        $this->app->bind(AttendanceServiceInterface::class, AttendanceService::class);
        $this->app->bind(AttendanceExceptionRequestServiceInterface::class, AttendanceExceptionRequestService::class);
        $this->app->bind(AttendanceCorrectionServiceInterface::class, AttendanceCorrectionService::class);
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
