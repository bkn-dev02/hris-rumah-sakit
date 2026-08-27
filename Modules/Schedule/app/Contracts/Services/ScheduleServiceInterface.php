<?php

namespace Modules\Schedule\Contracts\Services;

use Carbon\Carbon;
use Modules\Schedule\Models\Schedule;

interface ScheduleServiceInterface
{
    public function assign(int $employeeId, Carbon $date, string $type, ?int $shiftId, ?int $createdByEmployeeId, bool $syncToMaster = true, ?int $actorUserId = null): Schedule;

    public function resolveEffectiveShift(int $employeeId, Carbon $date): array;

    public function getDistribution(Carbon $startDate, Carbon $endDate, ?int $departmentId = null): array;

    public function getMonthlyGrid(int $departmentId, int $year, int $month): array;

    public function getScheduleMapForGrid(array $employeeIds, Carbon $startDate, Carbon $endDate): array;
}
