<?php

namespace Modules\Attendance\Repositories;

use Modules\Attendance\Contracts\Repositories\CheckInRepositoryInterface;
use Modules\Attendance\Models\CheckIn;
use Illuminate\Support\Collection;

class CheckInRepository implements CheckInRepositoryInterface
{
    protected CheckIn $model;

    public function __construct(CheckIn $model)
    {
        $this->model = $model;
    }

    public function findById(int $id): ?CheckIn
    {
        return $this->model->with(['employee', 'location'])->find($id);
    }

    public function create(array $data): CheckIn
    {
        return $this->model->create($data);
    }

    public function update(CheckIn $checkIn, array $data): bool
    {
        return $checkIn->update($data);
    }

    public function delete(CheckIn $checkIn): bool
    {
        return (bool) $checkIn->delete();
    }

    public function findByEmployeeAndDate(int $employeeId, string $date): ?CheckIn
    {
        return $this->model
            ->where('employee_id', $employeeId)
            ->whereDate('checked_at', $date)
            ->first();
    }

    public function findTodayByEmployeeAndType(int $employeeId, string $type): ?CheckIn
    {
        return $this->model
            ->where('employee_id', $employeeId)
            ->where('type', $type)
            ->whereDate('checked_at', now()->toDateString())
            ->latest('checked_at')
            ->first();
    }

    public function allByEmployeeAndType(int $employeeId, string $type): Collection
    {
        return $this->model
            ->where('employee_id', $employeeId)
            ->where('type', $type)
            ->orderByDesc('checked_at')
            ->get();
    }
}
