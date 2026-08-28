<?php

namespace Modules\Schedule\Services;

use Carbon\Carbon;
use Modules\Schedule\Contracts\Repositories\ScheduleRepositoryInterface;
use Modules\Schedule\Contracts\Services\ScheduleServiceInterface;
use Modules\Schedule\Models\Schedule;
use Modules\Master\Contracts\Services\EmployeeShiftScheduleServiceInterface;
use Modules\Master\Models\Employee;
use Modules\Master\DTOs\EmployeeShiftScheduleData;

class ScheduleService implements ScheduleServiceInterface
{
    public function __construct(
        protected ScheduleRepositoryInterface $scheduleRepository
    ) {}

    public function assign(int $employeeId, Carbon $date, string $type, ?int $shiftId, ?int $createdByEmployeeId, bool $syncToMaster = true, ?int $actorUserId = null): Schedule
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use (
            $employeeId,
            $date,
            $type,
            $shiftId,
            $createdByEmployeeId,
            $syncToMaster,
            $actorUserId
        ) {
            $existing = $this->scheduleRepository->findByEmployeeAndDate($employeeId, $date);

            $data = [
                'employee_id' => $employeeId,
                'date' => $date,
                'type' => $type,
                'shift_id' => $type === 'libur' ? null : $shiftId,
                'created_by' => $createdByEmployeeId,
            ];

            $schedule = $existing
                ? $this->scheduleRepository->update($existing, $data)
                : $this->scheduleRepository->create($data);

            if ($syncToMaster && $type === 'kerja' && $shiftId) {
                $this->syncToMasterDefaultShift($employeeId, $date, $shiftId, $actorUserId);
            }

            return $schedule;
        });
    }

    protected function syncToMasterDefaultShift(int $employeeId, Carbon $date, int $shiftId, ?int $actorUserId): void
    {
        $employee = Employee::find($employeeId);
        $currentShift = $employee?->currentShift();

        if ($currentShift?->id === $shiftId) {
            return;
        }

        app(EmployeeShiftScheduleServiceInterface::class)
            ->createSchedule(EmployeeShiftScheduleData::fromArray([
                'employee_id' => $employeeId,
                'shift_id' => $shiftId,
                'start_date' => $date->toDateString(),
                'created_by' => $actorUserId,
            ]));
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
                'shift_label' => $schedule->shift?->initials,
            ];
        }

        return $map;
    }

    public function getEmployeeSchedule(int $employeeId, Carbon $startDate, Carbon $endDate): array
    {
        $schedules = $this->scheduleRepository
            ->getForEmployeeAndDateRange($employeeId, $startDate, $endDate)
            ->keyBy(fn($s) => $s->date->toDateString());

        $result = [];
        $cursor = $startDate->copy();

        while ($cursor->lte($endDate)) {
            $key = $cursor->toDateString();
            $schedule = $schedules->get($key);

            if (! $schedule) {
                $result[] = [
                    'date' => $key,
                    'is_libur' => false,
                    'shift' => null,
                ];
            } elseif ($schedule->isLibur()) {
                $result[] = [
                    'date' => $key,
                    'is_libur' => true,
                    'shift' => null,
                ];
            } else {
                $result[] = [
                    'date' => $key,
                    'is_libur' => false,
                    'shift' => $this->formatShiftForSchedule($schedule->shift),
                ];
            }

            $cursor->addDay();
        }

        return $result;
    }

    protected function formatShiftForSchedule(?\Modules\Master\Models\Shift $shift): ?array
    {
        if (! $shift) {
            return null;
        }

        return [
            'id' => $shift->id,
            'code' => $shift->code,
            'name' => $shift->name,
            'start_time' => $shift->start_time ? \Carbon\Carbon::parse($shift->start_time)->format('H:i') : null,
            'end_time' => $shift->end_time ? \Carbon\Carbon::parse($shift->end_time)->format('H:i') : null,
        ];
    }

    public function resolveDisplayStatus(int $employeeId, Carbon $date): array
    {
        $schedule = $this->scheduleRepository->findByEmployeeAndDate($employeeId, $date);

        if (! $schedule) {
            return [
                'is_libur' => false,
                'is_undetermined' => true,
                'shift_id' => null,
            ];
        }

        return [
            'is_libur' => $schedule->isLibur(),
            'is_undetermined' => false,
            'shift_id' => $schedule->shift_id,
        ];
    }
}
