<?php

namespace Modules\Leave\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Leave\Contracts\Repositories\LeaveRequestRepositoryInterface;
use Modules\Leave\Contracts\Repositories\LeaveTypeRepositoryInterface;
use Modules\Leave\Contracts\Services\LeaveRequestServiceInterface;
use Modules\Leave\DTOs\LeaveRequestData;
use Modules\Leave\Models\LeaveRequest;
use Modules\Master\Models\Employee;
use RuntimeException;

class LeaveRequestService implements LeaveRequestServiceInterface
{
    public function __construct(
        protected LeaveRequestRepositoryInterface $leaveRequestRepository,
        protected LeaveTypeRepositoryInterface $leaveTypeRepository,
    ) {}

    public function getLeaveTypesWithQuota(Employee $employee): Collection
    {
        $leaveTypes = $this->leaveTypeRepository->allActive();
        $year = now()->year;

        return $leaveTypes->map(function ($leaveType) use ($employee, $year) {
            $quota = $this->leaveTypeRepository->quotaFor($employee->id, $leaveType->id, $year);
            $used = $this->leaveRequestRepository->usedDaysByEmployeeAndType($employee->id, $leaveType->id, $year);

            $leaveType->remaining_quota = max(0, $quota - $used);

            return $leaveType;
        });
    }

    public function submit(LeaveRequestData $data): LeaveRequest
    {
        $employee = Employee::query()->findOrFail($data->employeeId);
        $leaveType = $this->leaveTypeRepository->find($data->leaveTypeId);

        if (! $leaveType) {
            throw new RuntimeException('Jenis cuti tidak ditemukan.');
        }

        $year = now()->year;
        $quota = $this->leaveTypeRepository->quotaFor($employee->id, $leaveType->id, $year);
        $used = $this->leaveRequestRepository->usedDaysByEmployeeAndType($employee->id, $leaveType->id, $year);

        if ($used + 1 > $quota) {
            throw new RuntimeException('Kuota cuti tidak cukup.');
        }

        if ($this->leaveRequestRepository->hasOverlapping($employee->id, $data->startDate, $data->endDate)) {
            throw new RuntimeException('Tanggal cuti bentrok dengan pengajuan lain.');
        }

        return $this->leaveRequestRepository->create([
            'employee_id' => $employee->id,
            'leave_type_id' => $leaveType->id,
            'start_date' => $data->startDate,
            'end_date' => $data->endDate,
            'total_days' => 1,
            'reason' => $data->reason,
            'attachment' => $data->attachment,
            'status' => 'pending',
        ]);
    }

    public function myRequests(Employee $employee): Collection
    {
        return $this->leaveRequestRepository->allByEmployee($employee->id);
    }

    public function findMyRequest(int $id, Employee $employee): ?LeaveRequest
    {
        return $this->leaveRequestRepository->findByEmployee($id, $employee->id);
    }
}
