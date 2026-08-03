<?php

namespace Modules\Security\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Security\Contracts\Repositories\ActivityLogRepositoryInterface;
use Modules\Security\Models\ActivityLog;

class ActivityLogRepository implements ActivityLogRepositoryInterface
{
    protected ActivityLog $model;

    public function __construct(ActivityLog $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->model
            ->with('causer')
            ->when($filters['log_name'] ?? null, fn($query, $value) => $query->where('log_name', $value))
            ->when($filters['event'] ?? null, fn($query, $value) => $query->where('event', $value))
            ->when($filters['causer_id'] ?? null, fn($query, $value) => $query->where('causer_id', $value))
            ->when($filters['start_date'] ?? null, fn($query, $value) => $query->whereDate('created_at', '>=', $value))
            ->when($filters['end_date'] ?? null, fn($query, $value) => $query->whereDate('created_at', '<=', $value))
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function historyForSubject(string $subjectType, int $subjectId): Collection
    {
        return $this->model
            ->with('causer')
            ->where('subject_type', $subjectType)
            ->where('subject_id', $subjectId)
            ->orderByDesc('created_at')
            ->get();
    }
}
