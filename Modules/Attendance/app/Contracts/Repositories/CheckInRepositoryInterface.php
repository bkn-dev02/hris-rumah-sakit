<?php

namespace Modules\Attendance\Contracts\Repositories;

use Modules\Attendance\Models\CheckIn;
use Illuminate\Support\Collection;

interface CheckInRepositoryInterface
{
    public function findById(int $id): ?CheckIn;

    public function create(array $data): CheckIn;

    public function update(CheckIn $checkIn, array $data): bool;

    public function delete(CheckIn $checkIn): bool;

    public function findByEmployeeAndDate(int $employeeId, string $date): ?CheckIn;

    public function findTodayByEmployeeAndType(int $employeeId, string $type): ?CheckIn;

    public function allByEmployeeAndType(int $employeeId, string $type): Collection;
}
