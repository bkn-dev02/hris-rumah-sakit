<?php

namespace Modules\Attendance\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Attendance\Models\AttendanceExceptionRequest;

interface AttendanceExceptionRequestRepositoryInterface
{
    public function paginate(int $perPage = 15, ?string $approvalStatus = null): LengthAwarePaginator;

    public function findById(int $id): ?AttendanceExceptionRequest;

    public function findApprovedForDate(int $employeeId, string $workDate): ?AttendanceExceptionRequest;

    public function history(int $employeeId): Collection;

    public function create(array $data): AttendanceExceptionRequest;

    public function update(AttendanceExceptionRequest $request, array $data): bool;

    public function countApprovedForDate(string $date): int;
}
