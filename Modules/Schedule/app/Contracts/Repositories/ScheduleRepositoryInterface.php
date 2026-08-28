<?php

namespace Modules\Schedule\Contracts\Repositories;

use Carbon\Carbon;
use Modules\Schedule\Models\Schedule;

interface ScheduleRepositoryInterface
{
    public function find(int $id): ?Schedule;

    public function findByEmployeeAndDate(int $employeeId, Carbon $date): ?Schedule;

    public function create(array $data): Schedule;

    public function update(Schedule $schedule, array $data): Schedule;

    /**
     * Get the most recent explicit schedule entry before a given date, for fallback shift resolution.
     */
    public function findLatestBeforeDate(int $employeeId, Carbon $date): ?Schedule;

    /**
     * Get all schedule entries for a date range, optionally filtered by department (via employee relation).
     */
    public function getForDateRange(Carbon $startDate, Carbon $endDate, ?int $departmentId = null);

    public function getForMonth(int $departmentId, int $year, int $month);

    public function getForEmployeesAndDateRange(array $employeeIds, Carbon $startDate, Carbon $endDate);

    public function getForEmployeeAndDateRange(int $employeeId, Carbon $startDate, Carbon $endDate);
}
