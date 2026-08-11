<?php

namespace Modules\Leave\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Leave\Contracts\Repositories\LeaveTypeRepositoryInterface;
use Modules\Leave\Models\LeaveType;
use Modules\Leave\Models\EmployeeLeaveQuota;

class LeaveTypeRepository implements LeaveTypeRepositoryInterface
{
    public function allActive(): Collection
    {
        return LeaveType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function find(int $id): ?LeaveType
    {
        return LeaveType::query()->find($id);
    }

    public function quotaFor(int $employeeId, int $leaveTypeId, int $year): int
    {
        return EmployeeLeaveQuota::query()
            ->where('employee_id', $employeeId)
            ->where('leave_type_id', $leaveTypeId)
            ->where('year', $year)
            ->value('quota_days') ?? 0;
    }

    public function allActiveRequiringQuota(): Collection
    {
        return LeaveType::query()
            ->where('is_active', true)
            ->where('requires_quota', true)
            ->orderBy('name')
            ->get();
    }
}
