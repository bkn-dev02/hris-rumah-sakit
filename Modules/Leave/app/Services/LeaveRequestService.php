<?php

namespace Modules\Leave\Services;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Attendance\Models\Attendance;
use Modules\Attendance\Models\AttendanceStatus;
use Modules\Leave\Contracts\Repositories\LeaveRequestRepositoryInterface;
use Modules\Leave\Contracts\Repositories\LeaveTypeRepositoryInterface;
use Modules\Leave\Contracts\Services\LeaveRequestServiceInterface;
use Modules\Leave\DTOs\LeaveRequestData;
use Modules\Leave\Models\Holiday;
use Modules\Leave\Models\LeaveRequest;
use Modules\Master\Models\Employee;
use RuntimeException;

class LeaveRequestService implements LeaveRequestServiceInterface
{
    public function __construct(
        protected LeaveRequestRepositoryInterface $leaveRequestRepository,
        protected LeaveTypeRepositoryInterface $leaveTypeRepository,
        protected ApprovalChainBuilder $approvalChainBuilder,
    ) {}

    public function getLeaveTypesWithQuota(Employee $employee): Collection
    {
        $leaveTypes = $this->leaveTypeRepository->allActive();
        $year = now()->year;

        return $leaveTypes->map(function ($leaveType) use ($employee, $year) {
            if (! $leaveType->requires_quota) {
                $leaveType->remaining_quota = null;
                return $leaveType;
            }

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

        if (! $leaveType || ! $leaveType->is_active) {
            throw new RuntimeException('Jenis cuti tidak ditemukan atau tidak aktif.');
        }

        if (\Carbon\Carbon::parse($data->startDate)->startOfDay()->isPast()) {
            throw new RuntimeException('Tanggal mulai cuti tidak boleh di masa lalu.');
        }

        if ($this->leaveRequestRepository->hasOverlapping($employee->id, $data->startDate, $data->endDate)) {
            throw new RuntimeException('Tanggal cuti bentrok dengan pengajuan lain.');
        }

        $totalDays = $this->calculateWorkingDays($data->startDate, $data->endDate);

        if ($totalDays < 1) {
            throw new RuntimeException('Rentang tanggal cuti tidak valid (tidak ada hari kerja).');
        }

        if ($leaveType->requires_quota) {
            $year = now()->year;
            $quota = $this->leaveTypeRepository->quotaFor($employee->id, $leaveType->id, $year);
            $used = $this->leaveRequestRepository->usedDaysByEmployeeAndType($employee->id, $leaveType->id, $year);

            if ($used + $totalDays > $quota) {
                throw new RuntimeException("Kuota cuti tidak cukup. Sisa kuota: " . max(0, $quota - $used) . " hari.");
            }
        }

        $chain = $this->approvalChainBuilder->build($employee);

        return $this->leaveRequestRepository->createWithApprovalChain([
            'employee_id' => $employee->id,
            'leave_type_id' => $leaveType->id,
            'start_date' => $data->startDate,
            'end_date' => $data->endDate,
            'total_days' => $totalDays,
            'reason' => $data->reason,
            'attachment' => $data->attachment,
            'status' => 'pending',
        ], $chain);
    }

    public function myRequests(Employee $employee): Collection
    {
        return $this->leaveRequestRepository->allByEmployee($employee->id);
    }

    public function findMyRequest(int $id, Employee $employee): ?LeaveRequest
    {
        return $this->leaveRequestRepository->findByEmployee($id, $employee->id);
    }

    protected function calculateWorkingDays(string $startDate, string $endDate): int
    {
        $start = \Carbon\Carbon::parse($startDate)->startOfDay();
        $end = \Carbon\Carbon::parse($endDate)->startOfDay();

        if ($start->gt($end)) {
            return 0;
        }

        $holidays = Holiday::query()
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->pluck('date')
            ->map(fn($date) => $date->toDateString())
            ->all();

        $totalDays = 0;
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            $isWeekend = $cursor->isWeekend();
            $isHoliday = in_array($cursor->toDateString(), $holidays, true);

            if (! $isWeekend && ! $isHoliday) {
                $totalDays++;
            }

            $cursor->addDay();
        }

        return $totalDays;
    }

    public function pendingForApprover(Employee $approver): Collection
    {
        return $this->leaveRequestRepository->allPendingForApprover($approver->id);
    }

    public function decide(int $leaveRequestId, Employee $approver, bool $approve, ?string $note = null): LeaveRequest
    {
        $leaveRequest = $this->leaveRequestRepository->find($leaveRequestId);

        if (! $leaveRequest || $leaveRequest->status !== 'pending') {
            throw new RuntimeException('Pengajuan cuti tidak ditemukan atau sudah diproses.');
        }

        $currentStep = $leaveRequest->currentApproval();

        if (! $currentStep || $currentStep->approver_employee_id !== $approver->id) {
            throw new RuntimeException('Belum giliran Anda untuk memutuskan pengajuan ini.');
        }

        $currentStep->update([
            'status' => $approve ? 'approved' : 'rejected',
            'decided_at' => now(),
            'note' => $note,
        ]);

        if (! $approve) {
            $leaveRequest->update(['status' => 'rejected']);
        } elseif ($leaveRequest->approvals()->where('status', 'pending')->doesntExist()) {
            $leaveRequest->update(['status' => 'approved']);
            $this->createApprovedLeaveAttendance($leaveRequest);
        }

        return $leaveRequest->fresh('approvals.approver');
    }

    protected function createApprovedLeaveAttendance(LeaveRequest $leaveRequest): void
    {
        $employee = $leaveRequest->employee()->first();

        if (! $employee) {
            return;
        }

        $status = $this->resolveLeaveAttendanceStatus();

        $dates = CarbonPeriod::create($leaveRequest->start_date, $leaveRequest->end_date);

        foreach ($dates as $date) {
            $workDate = $date->toDateString();
            $shift = $employee->activeShiftFor($workDate) ?? $employee->currentShift();

            if (! $shift) {
                continue;
            }

            Attendance::query()->updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'work_date' => $workDate,
                ],
                [
                    'shift_id' => $shift->id,
                    'attendance_status_id' => $status->id,
                    'determination_type' => 'manual',
                    'source' => 'manual',
                    'notes' => 'Cuti disetujui: ' . ($leaveRequest->leaveType->name ?? 'Cuti'),
                ]
            );
        }
    }

    protected function resolveLeaveAttendanceStatus(): AttendanceStatus
    {
        $status = AttendanceStatus::query()
            ->whereIn('code', ['CUTI', 'LEAVE'])
            ->first();

        if ($status) {
            return $status;
        }

        return AttendanceStatus::query()->firstOrCreate(
            ['code' => 'CUTI'],
            [
                'name' => 'Cuti',
                'category' => 'exception',
                'determination_type' => 'manual',
                'is_active' => true,
            ]
        );
    }

    public function allRequests(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->leaveRequestRepository->paginateAll($filters, $perPage);
    }

    public function cancel(int $leaveRequestId, Employee $employee): LeaveRequest
    {
        $leaveRequest = $this->leaveRequestRepository->findCancellableByEmployee($leaveRequestId, $employee->id);

        if (! $leaveRequest) {
            throw new RuntimeException('Pengajuan cuti tidak ditemukan atau sudah tidak bisa dibatalkan.');
        }

        return $this->leaveRequestRepository->updateStatus($leaveRequest, [
            'status' => 'cancelled',
        ]);
    }
}
