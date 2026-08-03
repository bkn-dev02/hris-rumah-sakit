<?php

namespace Modules\Security\Listeners;

use Illuminate\Auth\Events\Failed;
use Modules\Security\Contracts\Services\LoginHistoryServiceInterface;

class LogFailedLogin
{
    public function __construct(
        protected LoginHistoryServiceInterface $loginHistoryService
    ) {}

    public function handle(Failed $event): void
    {
        $usernameAttempted = $event->credentials['username']
            ?? $event->credentials['email']
            ?? 'unknown';

        $reason = $event->user ? 'invalid_password' : 'user_not_found';

        $this->loginHistoryService->recordFailed(
            $usernameAttempted,
            request()->ip(),
            request()->userAgent(),
            $reason
        );
    }
}
