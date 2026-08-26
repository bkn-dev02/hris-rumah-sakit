<?php

namespace Modules\Schedule\Repositories;

use Carbon\Carbon;
use Modules\Schedule\Contracts\Repositories\ScheduleRepositoryInterface;
use Modules\Schedule\Models\Schedule;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function find(int $id): ?Schedule
    {
        return Schedule::find($id);
    }

    public function findByEmployeeAndDate(int $employeeId, Carbon $date): ?Schedule
    {
        return Schedule::where('employee_id', $employeeId)
            ->whereDate('date', $date)
            ->first();
    }

    public function create(array $data): Schedule
    {
        return Schedule::create($data);
    }

    public function update(Schedule $schedule, array $data): Schedule
    {
        $schedule->update($data);

        return $schedule->fresh();
    }

    public function findLatestBeforeDate(int $employeeId, Carbon $date): ?Schedule
    {
        return Schedule::where('employee_id', $employeeId)
            ->where('type', 'kerja')
            ->whereDate('date', '<', $date)
            ->orderByDesc('date')
            ->first();
    }

    public function getForDateRange(Carbon $startDate, Carbon $endDate, ?int $departmentId = null)
    {
        return Schedule::whereBetween('date', [$startDate, $endDate])
            ->when($departmentId, function ($query) use ($departmentId) {
                $query->whereHas('employee.placements', function ($q) use ($departmentId) {
                    $q->active()->where('department_id', $departmentId);
                });
            })
            ->with(['employee', 'shift'])
            ->get();
    }

    public function getForMonth(int $departmentId, int $year, int $month)
    {
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        return $this->getForDateRange($startDate, $endDate, $departmentId);
    }

    public function getForEmployeesAndDateRange(array $employeeIds, Carbon $startDate, Carbon $endDate)
    {
        return Schedule::whereIn('employee_id', $employeeIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('shift')
            ->get();
    }
}
