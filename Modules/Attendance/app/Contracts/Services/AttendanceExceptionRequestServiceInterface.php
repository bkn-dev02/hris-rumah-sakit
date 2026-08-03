<?php

namespace Modules\Attendance\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Attendance\DTOs\AttendanceExceptionRequestData;
use Modules\Attendance\Models\AttendanceExceptionRequest;

interface AttendanceExceptionRequestServiceInterface
{
    public function paginate(int $perPage = 15, ?string $approvalStatus = null): LengthAwarePaginator;

    public function findById(int $id): AttendanceExceptionRequest;

    public function history(int $employeeId): Collection;

    public function submit(AttendanceExceptionRequestData $data): AttendanceExceptionRequest;

    public function approve(int $id, int $approvedBy): AttendanceExceptionRequest;

    public function reject(int $id, string $rejectionReason, int $approvedBy): AttendanceExceptionRequest;
}
