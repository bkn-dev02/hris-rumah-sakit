<?php

namespace Modules\Schedule\Repositories;

use Carbon\Carbon;
use Modules\Schedule\Contracts\Repositories\ManualConfirmationRepositoryInterface;
use Modules\Schedule\Models\ManualConfirmation;

class ManualConfirmationRepository implements ManualConfirmationRepositoryInterface
{
    public function create(array $data): ManualConfirmation
    {
        return ManualConfirmation::create($data);
    }

    public function findByEmployeeDateShift(int $employeeId, Carbon $date, int $shiftId): ?ManualConfirmation
    {
        return ManualConfirmation::where('employee_id', $employeeId)
            ->whereDate('date', $date)
            ->where('shift_id', $shiftId)
            ->first();
    }

    public function existsForEmployeeDateShift(int $employeeId, Carbon $date, int $shiftId): bool
    {
        return $this->findByEmployeeDateShift($employeeId, $date, $shiftId) !== null;
    }
}
