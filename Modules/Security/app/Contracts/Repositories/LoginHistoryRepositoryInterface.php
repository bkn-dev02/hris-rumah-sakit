<?php

namespace Modules\Security\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Security\Models\LoginHistory;

interface LoginHistoryRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function create(array $data): LoginHistory;

    public function countRecentFailedAttempts(string $username, int $withinMinutes = 15): int;
}
