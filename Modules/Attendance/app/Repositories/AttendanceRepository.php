<?php

namespace Modules\Attendance\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Attendance\Contracts\Repositories\AttendanceRepositoryInterface;
use Modules\Attendance\Models\Attendance;

class AttendanceRepository implements AttendanceRepositoryInterface
{
    protected Attendance $model;

    public function __construct(Attendance $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->model
            ->with(['employee', 'shift', 'status'])
            ->when($filters['employee_id'] ?? null, fn($query, $value) => $query->where('employee_id', $value))
            ->when($filters['status_id'] ?? null, fn($query, $value) => $query->where('attendance_status_id', $value))
            ->when($filters['start_date'] ?? null, fn($query, $value) => $query->whereDate('work_date', '>=', $value))
            ->when($filters['end_date'] ?? null, fn($query, $value) => $query->whereDate('work_date', '<=', $value))
            ->orderByDesc('work_date')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $id): ?Attendance
    {
        return $this->model->with(['employee', 'shift', 'status', 'checkInLocation', 'checkOutLocation'])->find($id);
    }

    public function findByEmployeeAndDate(int $employeeId, string $workDate): ?Attendance
    {
        return $this->model
            ->where('employee_id', $employeeId)
            ->whereDate('work_date', $workDate)
            ->first();
    }

    public function history(int $employeeId, ?string $startDate = null, ?string $endDate = null): Collection
    {
        return $this->model
            ->with(['shift', 'status'])
            ->where('employee_id', $employeeId)
            ->when($startDate, fn($query, $value) => $query->whereDate('work_date', '>=', $value))
            ->when($endDate, fn($query, $value) => $query->whereDate('work_date', '<=', $value))
            ->orderByDesc('work_date')
            ->get();
    }

    public function incomplete(?string $beforeDate = null): Collection
    {
        return $this->model
            ->with('employee')
            ->incomplete()
            ->when($beforeDate, fn($query, $value) => $query->whereDate('work_date', '<', $value))
            ->get();
    }

    public function unresolved(): Collection
    {
        return $this->model
            ->with(['employee', 'shift'])
            ->unresolved()
            ->get();
    }

    public function create(array $data): Attendance
    {
        return $this->model->create($data);
    }

    public function update(Attendance $attendance, array $data): bool
    {
        return $attendance->update($data);
    }

    public function findOpenForEmployee(int $employeeId): ?Attendance
    {
        return $this->model
            ->with('shift')
            ->where('employee_id', $employeeId)
            ->orderByDesc('work_date')
            ->first();
    }

    public function countCheckedInForDate(string $date): int
    {
        return $this->model
            ->whereDate('work_date', $date)
            ->count();
    }

    public function recentForDate(string $date, int $limit = 10): Collection
    {
        return $this->model
            ->with(['employee', 'status'])
            ->whereDate('work_date', $date)
            ->limit($limit)
            ->get();
    }
}
