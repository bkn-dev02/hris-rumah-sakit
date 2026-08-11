<?php

namespace Modules\Leave\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Leave\Contracts\Repositories\LeaveRequestRepositoryInterface;
use Modules\Leave\Contracts\Repositories\LeaveTypeRepositoryInterface;
use Modules\Leave\Contracts\Services\LeaveRequestServiceInterface;
use Modules\Leave\DTOs\LeaveRequestData;
use Modules\Leave\Models\Holiday;
use Modules\Leave\Models\LeaveRequest;
use Modules\Master\Models\Employee;
use RuntimeException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

        $supervisor = $employee->directSupervisor();

        return $this->leaveRequestRepository->create([
            'employee_id' => $employee->id,
            'leave_type_id' => $leaveType->id,
            'start_date' => $data->startDate,
            'end_date' => $data->endDate,
            'total_days' => $totalDays,
            'reason' => $data->reason,
            'attachment' => $data->attachment,
            'status' => $supervisor ? 'pending_supervisor' : 'pending_hr',
            'supervisor_id' => $supervisor?->id,
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

    public function pendingForSupervisor(Employee $supervisor): Collection
    {
        return $this->leaveRequestRepository->allPendingBySupervisor($supervisor->id);
    }

    public function decideBySupervisor(int $leaveRequestId, Employee $supervisor, bool $approve, ?string $note = null): LeaveRequest
    {
        $leaveRequest = $this->leaveRequestRepository->findPendingSupervisor($leaveRequestId, $supervisor->id);

        if (! $leaveRequest) {
            throw new RuntimeException('Pengajuan cuti tidak ditemukan atau bukan wewenang Anda.');
        }

        return $this->leaveRequestRepository->updateStatus($leaveRequest, [
            'status' => $approve ? 'pending_hr' : 'rejected',
            'supervisor_decided_at' => now(),
            'supervisor_note' => $note,
        ]);
    }

    public function pendingForHr(): Collection
    {
        return $this->leaveRequestRepository->allPendingHr();
    }

    public function decideByHr(int $leaveRequestId, Employee $hrApprover, bool $approve, ?string $note = null): LeaveRequest
    {
        $leaveRequest = $this->leaveRequestRepository->findPendingHr($leaveRequestId);

        if (! $leaveRequest) {
            throw new RuntimeException('Pengajuan cuti tidak ditemukan atau sudah diproses.');
        }

        return $this->leaveRequestRepository->updateStatus($leaveRequest, [
            'status' => $approve ? 'approved' : 'rejected',
            'hr_id' => $hrApprover->id,
            'hr_decided_at' => now(),
            'hr_note' => $note,
        ]);
    }

    public function allRequests(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->leaveRequestRepository->paginateAll($filters, $perPage);
    }
}
