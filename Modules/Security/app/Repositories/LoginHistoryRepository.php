<?php

namespace Modules\Security\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Security\Contracts\Repositories\LoginHistoryRepositoryInterface;
use Modules\Security\Models\LoginHistory;

class LoginHistoryRepository implements LoginHistoryRepositoryInterface
{
    protected LoginHistory $model;

    public function __construct(LoginHistory $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->model
            ->with('user')
            ->when($filters['user_id'] ?? null, fn($query, $value) => $query->where('user_id', $value))
            ->when($filters['status'] ?? null, fn($query, $value) => $query->where('status', $value))
            ->when($filters['start_date'] ?? null, fn($query, $value) => $query->whereDate('created_at', '>=', $value))
            ->when($filters['end_date'] ?? null, fn($query, $value) => $query->whereDate('created_at', '<=', $value))
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): LoginHistory
    {
        return $this->model->create($data);
    }

    public function countRecentFailedAttempts(string $username, int $withinMinutes = 15): int
    {
        return $this->model
            ->where('username_attempted', $username)
            ->where('status', 'failed')
            ->where('created_at', '>=', now()->subMinutes($withinMinutes))
            ->count();
    }
}
