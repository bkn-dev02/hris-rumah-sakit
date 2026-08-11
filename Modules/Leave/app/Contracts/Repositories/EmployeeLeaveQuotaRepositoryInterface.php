<?php

namespace Modules\Leave\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface EmployeeLeaveQuotaRepositoryInterface
{
    public function forEmployeeYear(int $employeeId, int $year): Collection;

    public function upsert(int $employeeId, int $leaveTypeId, int $year, int $quotaDays): void;
}
