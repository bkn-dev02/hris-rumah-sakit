<?php

namespace Modules\Master\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Master\Models\EmployeeShiftSchedule;

interface EmployeeShiftScheduleRepositoryInterface
{
    public function historyByEmployee(int $employeeId): Collection;

    public function findActiveByEmployee(int $employeeId): ?EmployeeShiftSchedule;

    public function findById(int $id): ?EmployeeShiftSchedule;

    public function create(array $data): EmployeeShiftSchedule;

    public function update(EmployeeShiftSchedule $schedule, array $data): bool;
}
