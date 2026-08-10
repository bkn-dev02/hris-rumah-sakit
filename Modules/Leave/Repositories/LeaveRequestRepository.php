<?php

namespace Modules\Leave\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Leave\Contracts\Repositories\LeaveRequestRepositoryInterface;
use Modules\Leave\Models\LeaveRequest;

class LeaveRequestRepository implements LeaveRequestRepositoryInterface
{
    public function allByEmployee(int $employeeId): Collection
    {
        return LeaveRequest::query()
            ->where('employee_id', $employeeId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function find(int $id): ?LeaveRequest
    {
        return LeaveRequest::query()->find($id);
    }

    public function findByEmployee(int $id, int $employeeId): ?LeaveRequest
    {
        return LeaveRequest::query()
            ->where('id', $id)
            ->where('employee_id', $employeeId)
            ->first();
    }

    public function create(array $data): LeaveRequest
    {
        return LeaveRequest::query()->create($data);
    }

    public function usedDaysByEmployeeAndType(int $employeeId, int $leaveTypeId, int $year): int
    {
        return LeaveRequest::query()
            ->where('employee_id', $employeeId)
            ->where('leave_type_id', $leaveTypeId)
            ->whereYear('start_date', $year)
            ->sum('total_days');
    }

    public function hasOverlapping(int $employeeId, string $startDate, string $endDate): bool
    {
        return LeaveRequest::query()
            ->where('employee_id', $employeeId)
            ->where(function ($query) use ($startDate, $endDate): void {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($nested) use ($startDate, $endDate): void {
                        $nested->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();
    }
}
