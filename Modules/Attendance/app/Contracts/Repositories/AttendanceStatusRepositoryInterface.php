<?php

namespace Modules\Attendance\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Attendance\Models\AttendanceStatus;

interface AttendanceStatusRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function activeList(): Collection;

    public function findById(int $id): ?AttendanceStatus;

    public function findByCode(string $code): ?AttendanceStatus;

    public function autoDetermined(): Collection;

    public function create(array $data): AttendanceStatus;

    public function update(AttendanceStatus $status, array $data): bool;

    public function delete(AttendanceStatus $status): bool;
}
