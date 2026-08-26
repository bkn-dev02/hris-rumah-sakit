<?php

namespace Modules\Schedule\Services;

use Carbon\Carbon;
use Modules\Schedule\Contracts\Repositories\ScheduleRepositoryInterface;
use Modules\Schedule\Contracts\Services\ScheduleServiceInterface;
use Modules\Schedule\Models\Schedule;

class ScheduleService implements ScheduleServiceInterface
{
    public function __construct(
        protected ScheduleRepositoryInterface $scheduleRepository
    ) {}

    public function assign(int $employeeId, Carbon $date, string $type, ?int $shiftId, int $createdByEmployeeId): Schedule
    {
        $existing = $this->scheduleRepository->findByEmployeeAndDate($employeeId, $date);

        $data = [
            'employee_id' => $employeeId,
            'date' => $date,
            'type' => $type,
            'shift_id' => $type === 'libur' ? null : $shiftId,
            'created_by' => $createdByEmployeeId,
        ];

        if ($existing) {
            return $this->scheduleRepository->update($existing, $data);
        }

        return $this->scheduleRepository->create($data);
    }

    public function resolveEffectiveShift(int $employeeId, Carbon $date): array
    {
        $schedule = $this->scheduleRepository->findByEmployeeAndDate($employeeId, $date);

        if ($schedule) {
            return [
                'shift_id' => $schedule->shift_id,
                'is_libur' => $schedule->isLibur(),
                'is_fallback' => false,
            ];
        }

        // No explicit schedule -> fallback to last assigned shift placement
        $lastSchedule = $this->scheduleRepository->findLatestBeforeDate($employeeId, $date);

        return [
            'shift_id' => $lastSchedule?->shift_id,
            'is_libur' => false,
            'is_fallback' => true,
        ];
    }

    public function getDistribution(Carbon $startDate, Carbon $endDate, ?int $departmentId = null): array
    {
        $schedules = $this->scheduleRepository->getForDateRange($startDate, $endDate, $departmentId);

        return $schedules
            ->groupBy(fn(Schedule $s) => $s->employee->department_id . '-' . $s->shift_id)
            ->map(fn($group) => [
                'department_id' => $group->first()->employee->department_id,
                'shift_id' => $group->first()->shift_id,
                'employee_count' => $group->count(),
            ])
            ->values()
            ->toArray();
    }

    public function getMonthlyGrid(int $departmentId, int $year, int $month): array
    {
        $schedules = $this->scheduleRepository->getForMonth($departmentId, $year, $month);

        $grid = [];

        foreach ($schedules as $schedule) {
            $employeeId = $schedule->employee_id;

            $grid[$employeeId]['employee_name'] ??= $schedule->employee->name;
            $grid[$employeeId]['dates'][$schedule->date->format('Y-m-d')] =
                $schedule->isLibur() ? 'L' : ($schedule->shift->code ?? '-');
        }

        return array_values($grid);
    }

    public function getScheduleMapForGrid(array $employeeIds, Carbon $startDate, Carbon $endDate): array
    {
        $schedules = $this->scheduleRepository->getForEmployeesAndDateRange($employeeIds, $startDate, $endDate);

        $map = [];

        foreach ($schedules as $schedule) {
            $map[$schedule->employee_id][$schedule->date->toDateString()] = [
                'type' => $schedule->type,
                'shift_id' => $schedule->shift_id,
                'shift_label' => $schedule->shift ? strtoupper(substr($schedule->shift->name, 0, 1)) : null,
            ];
        }

        return $map;
    }
}
