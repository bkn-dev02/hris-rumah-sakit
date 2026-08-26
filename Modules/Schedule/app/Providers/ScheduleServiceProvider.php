<?php

namespace Modules\Schedule\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Modules\Schedule\Contracts\Repositories\ScheduleRepositoryInterface;
use Modules\Schedule\Contracts\Repositories\SpCandidateRepositoryInterface;
use Modules\Schedule\Contracts\Repositories\ManualConfirmationRepositoryInterface;
use Modules\Schedule\Contracts\Repositories\SpLetterRepositoryInterface;
use Modules\Schedule\Contracts\Services\ScheduleServiceInterface;
use Modules\Schedule\Contracts\Services\SpCandidateServiceInterface;
use Modules\Schedule\Contracts\Services\SpLetterServiceInterface;
use Modules\Schedule\Repositories\ScheduleRepository;
use Modules\Schedule\Repositories\SpCandidateRepository;
use Modules\Schedule\Repositories\ManualConfirmationRepository;
use Modules\Schedule\Repositories\SpLetterRepository;
use Modules\Schedule\Services\ScheduleService;
use Modules\Schedule\Services\SpCandidateService;
use Modules\Schedule\Services\SpLetterService;

class ScheduleServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Schedule';

    protected string $nameLower = 'schedule';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->app->bind(ScheduleRepositoryInterface::class, ScheduleRepository::class);
        $this->app->bind(SpCandidateRepositoryInterface::class, SpCandidateRepository::class);
        $this->app->bind(ManualConfirmationRepositoryInterface::class, ManualConfirmationRepository::class);
        $this->app->bind(SpLetterRepositoryInterface::class, SpLetterRepository::class);

        $this->app->bind(ScheduleServiceInterface::class, ScheduleService::class);
        $this->app->bind(SpCandidateServiceInterface::class, SpCandidateService::class);
        $this->app->bind(SpLetterServiceInterface::class, SpLetterService::class);
    }
}
