<?php

namespace Modules\Attendance\Contracts\Services;

use Modules\Attendance\Models\CheckIn;

interface CheckInServiceInterface
{
    public function findById(int $id): CheckIn;

    public function create(array $data): CheckIn;

    public function update(int $id, array $data): CheckIn;

    public function delete(int $id): bool;

    public function findByEmployeeAndDate(int $employeeId, string $workDate): ?CheckIn;
}
