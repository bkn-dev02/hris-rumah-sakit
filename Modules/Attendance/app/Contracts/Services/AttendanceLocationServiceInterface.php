<?php

namespace Modules\Attendance\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Attendance\Models\AttendanceLocation;

interface AttendanceLocationServiceInterface
{
    public function getAll(): Collection;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function activeList(): Collection;

    public function findById(int $id): AttendanceLocation;

    public function create(array $data): AttendanceLocation;

    public function update(int $id, array $data): AttendanceLocation;

    public function delete(int $id): bool;

    public function findMatchingLocation(float $latitude, float $longitude): ?AttendanceLocation;
}
