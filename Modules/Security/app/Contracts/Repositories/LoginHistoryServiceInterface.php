<?php

namespace Modules\Security\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Security\Models\User;

interface LoginHistoryServiceInterface
{
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function recordSuccess(User $user, string $ipAddress, ?string $userAgent): void;

    public function recordFailed(string $usernameAttempted, string $ipAddress, ?string $userAgent, string $reason): void;

    public function hasExceededFailedAttempts(string $username, int $maxAttempts = 5, int $withinMinutes = 15): bool;
}
