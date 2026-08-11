<?php

namespace Modules\Leave\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Leave\Models\LeaveType;

interface LeaveTypeRepositoryInterface
{
    public function allActive(): Collection;

    public function find(int $id): ?LeaveType;

    public function quotaFor(int $employeeId, int $leaveTypeId, int $year): int;

    public function allActiveRequiringQuota(): Collection;
}
