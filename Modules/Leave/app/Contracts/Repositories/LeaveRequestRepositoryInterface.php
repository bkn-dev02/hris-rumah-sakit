<?php

namespace Modules\Leave\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Leave\Models\LeaveRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LeaveRequestRepositoryInterface
{
    public function allByEmployee(int $employeeId): Collection;

    public function find(int $id): ?LeaveRequest;

    public function findByEmployee(int $id, int $employeeId): ?LeaveRequest;

    public function create(array $data): LeaveRequest;

    public function usedDaysByEmployeeAndType(int $employeeId, int $leaveTypeId, int $year): int;

    public function hasOverlapping(int $employeeId, string $startDate, string $endDate): bool;

    public function findPendingSupervisor(int $id, int $supervisorId): ?LeaveRequest;

    public function findPendingHr(int $id): ?LeaveRequest;

    public function updateStatus(LeaveRequest $leaveRequest, array $data): LeaveRequest;

    public function allPendingBySupervisor(int $supervisorId): Collection;

    public function allPendingHr(): Collection;

    public function paginateAll(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function findCancellableByEmployee(int $id, int $employeeId): ?LeaveRequest;
}
