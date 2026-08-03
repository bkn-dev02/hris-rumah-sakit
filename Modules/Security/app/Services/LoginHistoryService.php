<?php

namespace Modules\Security\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Security\Contracts\Repositories\LoginHistoryRepositoryInterface;
use Modules\Security\Contracts\Services\LoginHistoryServiceInterface;
use Modules\Security\Models\User;

class LoginHistoryService implements LoginHistoryServiceInterface
{
    public function __construct(
        protected LoginHistoryRepositoryInterface $loginHistoryRepository
    ) {}

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->loginHistoryRepository->paginate($perPage, $filters);
    }

    public function recordSuccess(User $user, string $ipAddress, ?string $userAgent): void
    {
        $this->loginHistoryRepository->create([
            'user_id' => $user->id,
            'username_attempted' => $user->username,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'status' => 'success',
        ]);
    }

    public function recordFailed(string $usernameAttempted, string $ipAddress, ?string $userAgent, string $reason): void
    {
        $this->loginHistoryRepository->create([
            'user_id' => null,
            'username_attempted' => $usernameAttempted,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'status' => 'failed',
            'failure_reason' => $reason,
        ]);
    }

    public function hasExceededFailedAttempts(string $username, int $maxAttempts = 5, int $withinMinutes = 15): bool
    {
        return $this->loginHistoryRepository->countRecentFailedAttempts($username, $withinMinutes) >= $maxAttempts;
    }
}
