<?php

namespace Modules\Leave\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Leave\Contracts\Repositories\LeaveRequestRepositoryInterface;
use Modules\Leave\Models\LeaveRequest;
use Modules\Leave\Models\LeaveRequestApproval;

class LeaveRequestRepository implements LeaveRequestRepositoryInterface
{
    public function allByEmployee(int $employeeId): Collection
    {
        return LeaveRequest::query()
            ->where('employee_id', $employeeId)
            ->with('approvals.approver')
            ->orderByDesc('created_at')
            ->get();
    }

    public function find(int $id): ?LeaveRequest
    {
        return LeaveRequest::query()->with('approvals.approver')->find($id);
    }

    public function findByEmployee(int $id, int $employeeId): ?LeaveRequest
    {
        return LeaveRequest::query()
            ->where('id', $id)
            ->where('employee_id', $employeeId)
            ->with('approvals.approver')
            ->first();
    }

    public function createWithApprovalChain(array $data, array $chain): LeaveRequest
    {
        return DB::transaction(function () use ($data, $chain) {
            $leaveRequest = LeaveRequest::query()->create($data);

            foreach ($chain as $step) {
                LeaveRequestApproval::query()->create([
                    'leave_request_id' => $leaveRequest->id,
                    'approver_employee_id' => $step['employee']->id,
                    'sequence' => $step['sequence'],
                    'type' => $step['type'],
                    'status' => 'pending',
                ]);
            }

            return $leaveRequest->fresh('approvals.approver');
        });
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
            ->whereIn('status', ['pending', 'approved'])
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

    public function allPendingForApprover(int $approverId): Collection
    {
        return LeaveRequest::query()
            ->where('status', 'pending')
            ->whereHas('approvals', fn($q) => $q->where('approver_employee_id', $approverId)->where('status', 'pending'))
            ->with(['employee', 'leaveType', 'approvals.approver'])
            ->get()
            ->filter(fn(LeaveRequest $lr) => $lr->currentApproval()?->approver_employee_id === $approverId)
            ->values();
    }

    public function updateStatus(LeaveRequest $leaveRequest, array $data): LeaveRequest
    {
        $leaveRequest->update($data);

        return $leaveRequest->fresh('approvals.approver');
    }

    public function paginateAll(array $filters, int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return LeaveRequest::query()
            ->with(['employee', 'leaveType', 'approvals.approver'])
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
            ->where('status', 'pending')
            ->first();
    }
}
