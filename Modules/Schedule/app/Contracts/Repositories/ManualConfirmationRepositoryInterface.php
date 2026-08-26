<?php

namespace Modules\Schedule\Contracts\Repositories;

use Carbon\Carbon;
use Modules\Schedule\Models\ManualConfirmation;

interface ManualConfirmationRepositoryInterface
{
    public function create(array $data): ManualConfirmation;

    public function findByEmployeeDateShift(int $employeeId, Carbon $date, int $shiftId): ?ManualConfirmation;

    public function existsForEmployeeDateShift(int $employeeId, Carbon $date, int $shiftId): bool;
}
