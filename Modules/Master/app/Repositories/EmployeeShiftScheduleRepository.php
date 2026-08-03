<?php

namespace Modules\Master\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Master\Contracts\Repositories\EmployeeShiftScheduleRepositoryInterface;
use Modules\Master\Models\EmployeeShiftSchedule;

class EmployeeShiftScheduleRepository implements EmployeeShiftScheduleRepositoryInterface
{
    protected EmployeeShiftSchedule $model;

    public function __construct(EmployeeShiftSchedule $model)
    {
        $this->model = $model;
    }

    public function historyByEmployee(int $employeeId): Collection
    {
        return $this->model
            ->with('shift', 'createdBy')
            ->where('employee_id', $employeeId)
            ->orderByDesc('start_date')
            ->get();
    }

    public function findActiveByEmployee(int $employeeId): ?EmployeeShiftSchedule
    {
        return $this->model
            ->with('shift')
            ->where('employee_id', $employeeId)
            ->whereNull('end_date')
            ->latest('start_date')
            ->first();
    }

    public function findById(int $id): ?EmployeeShiftSchedule
    {
        return $this->model->with('shift')->find($id);
    }

    public function create(array $data): EmployeeShiftSchedule
    {
        return $this->model->create($data);
    }

    public function update(EmployeeShiftSchedule $schedule, array $data): bool
    {
        return $schedule->update($data);
    }
}
