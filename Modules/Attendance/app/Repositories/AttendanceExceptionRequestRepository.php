<?php

namespace Modules\Attendance\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Attendance\Contracts\Repositories\AttendanceExceptionRequestRepositoryInterface;
use Modules\Attendance\Models\AttendanceExceptionRequest;

class AttendanceExceptionRequestRepository implements AttendanceExceptionRequestRepositoryInterface
{
    protected AttendanceExceptionRequest $model;

    public function __construct(AttendanceExceptionRequest $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 15, ?string $approvalStatus = null): LengthAwarePaginator
    {
        return $this->model
            ->with(['employee', 'status'])
            ->when($approvalStatus, fn($query, $value) => $query->where('approval_status', $value))
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $id): ?AttendanceExceptionRequest
    {
        return $this->model->with(['employee', 'status', 'approvedBy'])->find($id);
    }

    public function findApprovedForDate(int $employeeId, string $workDate): ?AttendanceExceptionRequest
    {
        return $this->model
            ->with('status')
            ->where('employee_id', $employeeId)
            ->whereDate('work_date', $workDate)
            ->approved()
            ->first();
    }

    public function history(int $employeeId): Collection
    {
        return $this->model
            ->with('status')
            ->where('employee_id', $employeeId)
            ->orderByDesc('work_date')
            ->get();
    }

    public function create(array $data): AttendanceExceptionRequest
    {
        return $this->model->create($data);
    }

    public function update(AttendanceExceptionRequest $request, array $data): bool
    {
        return $request->update($data);
    }

    public function countApprovedForDate(string $date): int
    {
        return $this->model
            ->whereDate('work_date', $date)
            ->approved()
            ->count();
    }
}
