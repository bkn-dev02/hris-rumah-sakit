<?php

namespace Modules\Attendance\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Attendance\Models\CheckIn;

interface CheckInRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function findById(int $id): ?CheckIn;

    public function create(array $data): CheckIn;

    public function update(CheckIn $checkIn, array $data): bool;

    public function delete(CheckIn $checkIn): bool;

    public function findByEmployeeAndDate(int $employeeId, string $date): ?CheckIn;
}
