<?php

namespace Modules\Leave\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Leave\Contracts\Repositories\LeaveTypeRepositoryInterface;
use Modules\Leave\Models\LeaveType;

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
        return LeaveType::query()
            ->whereHas('quotas', function ($query) use ($employeeId, $leaveTypeId, $year): void {
                $query->where('employee_id', $employeeId)
                    ->where('leave_type_id', $leaveTypeId)
                    ->where('year', $year);
            })
            ->first()?->quotas()->where('employee_id', $employeeId)->where('year', $year)->sum('quota_days') ?? 0;
    }
}
