<?php

namespace Modules\Attendance\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Attendance\Models\AttendanceLocation;

interface AttendanceLocationRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function activeList(): Collection;

    public function findById(int $id): ?AttendanceLocation;

    public function create(array $data): AttendanceLocation;

    public function update(AttendanceLocation $location, array $data): bool;

    public function delete(AttendanceLocation $location): bool;
}
