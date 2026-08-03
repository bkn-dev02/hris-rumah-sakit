<?php

namespace Modules\Security\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Security\Contracts\Repositories\ActivityLogRepositoryInterface;
use Modules\Security\Contracts\Services\ActivityLogServiceInterface;

class ActivityLogService implements ActivityLogServiceInterface
{
    public function __construct(
        protected ActivityLogRepositoryInterface $activityLogRepository
    ) {}

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->activityLogRepository->paginate($perPage, $filters);
    }

    public function historyFor(Model $subject): Collection
    {
        return $this->activityLogRepository->historyForSubject(get_class($subject), $subject->getKey());
    }
}
