<?php

namespace Modules\Master\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Master\Contracts\Repositories\EmployeeShiftScheduleRepositoryInterface;
use Modules\Master\Contracts\Services\EmployeeShiftScheduleServiceInterface;
use Modules\Master\DTOs\EmployeeShiftScheduleData;
use Modules\Master\Models\EmployeeShiftSchedule;

class EmployeeShiftScheduleService implements EmployeeShiftScheduleServiceInterface
{
    public function __construct(
        protected EmployeeShiftScheduleRepositoryInterface $scheduleRepository
    ) {}

    public function history(int $employeeId): Collection
    {
        return $this->scheduleRepository->historyByEmployee($employeeId);
    }

    public function current(int $employeeId): ?EmployeeShiftSchedule
    {
        return $this->scheduleRepository->findActiveByEmployee($employeeId);
    }

    public function createSchedule(EmployeeShiftScheduleData $data): EmployeeShiftSchedule
    {
        return DB::transaction(function () use ($data) {

            $currentSchedule = $this->scheduleRepository->findActiveByEmployee($data->employeeId);

            if ($currentSchedule) {
                $this->scheduleRepository->update($currentSchedule, [
                    'end_date' => $data->startDate->copy()->subDay(),
                ]);
            }

            return $this->scheduleRepository->create([
                'employee_id' => $data->employeeId,
                'shift_id' => $data->shiftId,
                'start_date' => $data->startDate,
                'end_date' => null,
                'notes' => $data->notes,
                'created_by' => $data->createdBy,
            ]);
        });
    }
}
