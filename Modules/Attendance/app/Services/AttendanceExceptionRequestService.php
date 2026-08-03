<?php

namespace Modules\Attendance\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Modules\Attendance\Contracts\Repositories\AttendanceExceptionRequestRepositoryInterface;
use Modules\Attendance\Contracts\Services\AttendanceExceptionRequestServiceInterface;
use Modules\Attendance\DTOs\AttendanceExceptionRequestData;
use Modules\Attendance\Exceptions\AttendanceException;
use Modules\Attendance\Models\AttendanceExceptionRequest;

class AttendanceExceptionRequestService implements AttendanceExceptionRequestServiceInterface
{
    public function __construct(
        protected AttendanceExceptionRequestRepositoryInterface $exceptionRepository
    ) {}

    public function paginate(int $perPage = 15, ?string $approvalStatus = null): LengthAwarePaginator
    {
        return $this->exceptionRepository->paginate($perPage, $approvalStatus);
    }

    public function findById(int $id): AttendanceExceptionRequest
    {
        $request = $this->exceptionRepository->findById($id);

        if (!$request) {
            throw new ModelNotFoundException('Pengajuan tidak ditemukan.');
        }

        return $request;
    }

    public function history(int $employeeId): Collection
    {
        return $this->exceptionRepository->history($employeeId);
    }

    public function submit(AttendanceExceptionRequestData $data): AttendanceExceptionRequest
    {
        return DB::transaction(function () use ($data) {

            $existing = $this->exceptionRepository->findApprovedForDate($data->employeeId, $data->workDate->toDateString());

            if ($existing) {
                throw new AttendanceException('Sudah ada pengajuan yang disetujui untuk tanggal ini.');
            }

            return $this->exceptionRepository->create([
                'employee_id'           => $data->employeeId,
                'work_date'             => $data->workDate,
                'attendance_status_id'  => $data->attendanceStatusId,
                'reason'                => $data->reason,
                'attachment'            => $data->attachmentPath,
                'approval_status'       => 'pending',
            ]);
        });
    }

    public function approve(int $id, int $approvedBy): AttendanceExceptionRequest
    {
        return DB::transaction(function () use ($id, $approvedBy) {

            $request = $this->findById($id);

            if (!$request->isPending()) {
                throw new AttendanceException('Pengajuan ini sudah diproses sebelumnya.');
            }

            $this->exceptionRepository->update($request, [
                'approval_status'  => 'approved',
                'approved_by'      => $approvedBy,
                'approved_at'      => now(),
            ]);

            return $request->fresh();
        });
    }

    public function reject(int $id, string $rejectionReason, int $approvedBy): AttendanceExceptionRequest
    {
        return DB::transaction(function () use ($id, $rejectionReason, $approvedBy) {

            $request = $this->findById($id);

            if (!$request->isPending()) {
                throw new AttendanceException('Pengajuan ini sudah diproses sebelumnya.');
            }

            $this->exceptionRepository->update($request, [
                'approval_status'    => 'rejected',
                'approved_by'        => $approvedBy,
                'approved_at'        => now(),
                'rejection_reason'   => $rejectionReason,
            ]);

            return $request->fresh();
        });
    }
}
