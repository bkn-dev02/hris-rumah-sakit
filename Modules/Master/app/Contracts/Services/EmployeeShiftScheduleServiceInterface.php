<?php

namespace Modules\Master\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Master\DTOs\EmployeeShiftScheduleData;
use Modules\Master\Models\EmployeeShiftSchedule;

interface EmployeeShiftScheduleServiceInterface
{
    public function history(int $employeeId): Collection;

    public function current(int $employeeId): ?EmployeeShiftSchedule;

    public function createSchedule(EmployeeShiftScheduleData $data): EmployeeShiftSchedule;
}
