<?php

namespace Modules\Attendance\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Attendance\Models\AttendanceStatus;

interface AttendanceStatusServiceInterface
{
    public function getAll(): Collection;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function activeList(): Collection;

    public function findById(int $id): AttendanceStatus;

    public function create(array $data): AttendanceStatus;

    public function update(int $id, array $data): AttendanceStatus;

    public function delete(int $id): bool;
}
