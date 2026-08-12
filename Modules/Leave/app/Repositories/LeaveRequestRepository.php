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
            ->where('status', 'approved')
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

    public function findPendingSupervisor(int $id, int $supervisorId): ?LeaveRequest
    {
        return LeaveRequest::query()
            ->where('id', $id)
            ->where('supervisor_id', $supervisorId)
            ->where('status', 'pending_supervisor')
            ->first();
    }

    public function findPendingHr(int $id): ?LeaveRequest
    {
        return LeaveRequest::query()
            ->where('id', $id)
            ->where('status', 'pending_hr')
            ->first();
    }

    public function updateStatus(LeaveRequest $leaveRequest, array $data): LeaveRequest
    {
        $leaveRequest->update($data);

        return $leaveRequest->fresh();
    }

    public function allPendingBySupervisor(int $supervisorId): Collection
    {
        return LeaveRequest::query()
            ->where('supervisor_id', $supervisorId)
            ->where('status', 'pending_supervisor')
            ->orderBy('start_date')
            ->get();
    }

    public function allPendingHr(): Collection
    {
        return LeaveRequest::query()
            ->where('status', 'pending_hr')
            ->orderBy('start_date')
            ->get();
    }

    public function paginateAll(array $filters, int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return LeaveRequest::query()
            ->with(['employee', 'leaveType'])
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
            ->when($filters['leave_type_id'] ?? null, fn($q, $id) => $q->where('leave_type_id', $id))
            ->when($filters['year'] ?? null, fn($q, $year) => $q->whereYear('start_date', $year))
            ->when($filters['employee_search'] ?? null, function ($q, $search) {
                $q->whereHas('employee', fn($eq) => $eq->where('name', 'like', "%{$search}%"));
            })
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findCancellableByEmployee(int $id, int $employeeId): ?LeaveRequest
    {
        return LeaveRequest::query()
            ->where('id', $id)
            ->where('employee_id', $employeeId)
            ->whereIn('status', ['pending_supervisor', 'pending_hr'])
            ->first();
    }
}
