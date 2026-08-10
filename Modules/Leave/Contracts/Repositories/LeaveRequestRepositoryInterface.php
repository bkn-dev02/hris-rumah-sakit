<?php

namespace Modules\Leave\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Leave\Models\LeaveRequest;

interface LeaveRequestRepositoryInterface
{
    public function allByEmployee(int $employeeId): Collection;

    public function find(int $id): ?LeaveRequest;

    public function findByEmployee(int $id, int $employeeId): ?LeaveRequest;

    public function create(array $data): LeaveRequest;

    public function usedDaysByEmployeeAndType(int $employeeId, int $leaveTypeId, int $year): int;

    public function hasOverlapping(int $employeeId, string $startDate, string $endDate): bool;
}
