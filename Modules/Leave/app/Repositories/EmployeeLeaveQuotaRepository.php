<?php

namespace Modules\Leave\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Leave\Contracts\Repositories\EmployeeLeaveQuotaRepositoryInterface;
use Modules\Leave\Models\EmployeeLeaveQuota;

class EmployeeLeaveQuotaRepository implements EmployeeLeaveQuotaRepositoryInterface
{
    public function forEmployeeYear(int $employeeId, int $year): Collection
    {
        return EmployeeLeaveQuota::query()
            ->where('employee_id', $employeeId)
            ->where('year', $year)
            ->get()
            ->keyBy('leave_type_id');
    }

    public function upsert(int $employeeId, int $leaveTypeId, int $year, int $quotaDays): void
    {
        EmployeeLeaveQuota::query()->updateOrCreate(
            ['employee_id' => $employeeId, 'leave_type_id' => $leaveTypeId, 'year' => $year],
            ['quota_days' => $quotaDays]
        );
    }
}
