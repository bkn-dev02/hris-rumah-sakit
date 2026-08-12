<?php

namespace Modules\Leave\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Leave\DTOs\LeaveRequestData;
use Modules\Leave\Models\LeaveRequest;
use Modules\Master\Models\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LeaveRequestServiceInterface
{
    public function getLeaveTypesWithQuota(Employee $employee): Collection;

    public function submit(LeaveRequestData $data): LeaveRequest;

    public function myRequests(Employee $employee): Collection;

    public function findMyRequest(int $id, Employee $employee): ?LeaveRequest;

    public function pendingForSupervisor(Employee $supervisor): Collection;

    public function decideBySupervisor(int $leaveRequestId, Employee $supervisor, bool $approve, ?string $note = null): LeaveRequest;

    public function pendingForHr(): Collection;

    public function decideByHr(int $leaveRequestId, Employee $hrApprover, bool $approve, ?string $note = null): LeaveRequest;

    public function allRequests(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function cancel(int $leaveRequestId, Employee $employee): LeaveRequest;
}
