<?php

namespace Modules\Security\Listeners;

use Illuminate\Auth\Events\Login;
use Modules\Security\Contracts\Services\LoginHistoryServiceInterface;

class LogSuccessfulLogin
{
    public function __construct(
        protected LoginHistoryServiceInterface $loginHistoryService
    ) {}

    public function handle(Login $event): void
    {
        $this->loginHistoryService->recordSuccess(
            $event->user,
            request()->ip(),
            request()->userAgent()
        );
    }
}
