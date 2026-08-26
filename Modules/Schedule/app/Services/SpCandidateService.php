<?php

namespace Modules\Schedule\Services;

use Carbon\Carbon;
use Modules\Leave\Models\Holiday;
use Modules\Leave\Models\LeaveRequest;
use Modules\Attendance\Models\CheckIn;
use Modules\Schedule\Contracts\Repositories\ManualConfirmationRepositoryInterface;
use Modules\Schedule\Contracts\Repositories\SpCandidateRepositoryInterface;
use Modules\Schedule\Contracts\Services\SpCandidateServiceInterface;
use Modules\Schedule\Models\SpCandidate;

class SpCandidateService implements SpCandidateServiceInterface
{
    public function __construct(
        protected SpCandidateRepositoryInterface $spCandidateRepository,
        protected ManualConfirmationRepositoryInterface $manualConfirmationRepository
    ) {}

    public function runCheck(int $employeeId, Carbon $date, int $shiftId): ?SpCandidate
    {
        // Idempotency guard — one candidate per employee+date+shift
        $existing = $this->spCandidateRepository->findByEmployeeDateShift($employeeId, $date, $shiftId);
        if ($existing) {
            return $existing;
        }

        // 1. Hari libur nasional?
        if (Holiday::whereDate('date', $date)->exists()) {
            return null;
        }

        // 2. Sedang cuti approved?
        $onLeave = LeaveRequest::where('employee_id', $employeeId)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->exists();
        if ($onLeave) {
            return null;
        }

        // 3. Ada emergency attendance (pending/approved)?
        $hasEmergency = CheckIn::where('employee_id', $employeeId)
            ->whereDate('created_at', $date)
            ->where('type', 'emergency')
            ->whereIn('emergency_status', ['pending', 'approved'])
            ->exists();
        if ($hasEmergency) {
            return null;
        }

        // 4. Sudah ada Konfirmasi Manual sebelum candidate ini muncul?
        if ($this->manualConfirmationRepository->existsForEmployeeDateShift($employeeId, $date, $shiftId)) {
            return null;
        }

        // 5. Sudah check-in normal?
        $alreadyCheckedIn = CheckIn::where('employee_id', $employeeId)
            ->whereDate('created_at', $date)
            ->exists();
        if ($alreadyCheckedIn) {
            return null;
        }

        // Semua kondisi tidak terpenuhi -> buat SP Candidate
        $employee = \Modules\Master\Models\Employee::find($employeeId);
        $department = $employee->currentDepartment();
        if (!$department) {
            return null;
        }

        $spCandidate = $this->spCandidateRepository->create([
            'employee_id' => $employeeId,
            'date' => $date,
            'shift_id' => $shiftId,
            'department_id' => $department?->id,
            'status' => 'candidate',
            'detected_at' => now(),
        ]);

        // TODO: kirim notifikasi ke HRD, Direktur, dan Kepala Ruangan departemen ini

        return $spCandidate;
    }

    public function handleLateCheckin(int $employeeId, Carbon $date, int $shiftId, Carbon $checkedInAt): void
    {
        $spCandidate = $this->spCandidateRepository->findByEmployeeDateShift($employeeId, $date, $shiftId);

        if (!$spCandidate || $spCandidate->isResolved()) {
            return;
        }

        // Kalau sudah ada manual confirmation duluan, tidak perlu tanya HRD lagi
        if ($this->manualConfirmationRepository->existsForEmployeeDateShift($employeeId, $date, $shiftId)) {
            return;
        }

        $this->spCandidateRepository->update($spCandidate, [
            'status' => 'pending_decision',
            'late_checkin_at' => $checkedInAt,
        ]);

        // TODO: notifikasi ke HRD — "tetap terbitkan SP atau tidak?"
    }

    public function decide(int $spCandidateId, bool $issueSp, int $decidedByEmployeeId, ?string $note): SpCandidate
    {
        $spCandidate = $this->spCandidateRepository->find($spCandidateId);

        return $this->spCandidateRepository->update($spCandidate, [
            'status' => $issueSp ? 'candidate' : 'cancelled_late_checkin_decision',
            'decision_by' => $decidedByEmployeeId,
            'decision_at' => now(),
            'decision_note' => $note,
        ]);
        // Kalau $issueSp true, status dikembalikan ke 'candidate' supaya HRD lanjut proses issue via SpLetterService::issue()
    }

    public function recordManualConfirmation(int $employeeId, Carbon $date, int $shiftId, string $note, int $confirmedByEmployeeId, ?int $spCandidateId = null): void
    {
        $this->manualConfirmationRepository->create([
            'employee_id' => $employeeId,
            'date' => $date,
            'shift_id' => $shiftId,
            'sp_candidate_id' => $spCandidateId,
            'note' => $note,
            'confirmed_by' => $confirmedByEmployeeId,
        ]);

        if ($spCandidateId) {
            $spCandidate = $this->spCandidateRepository->find($spCandidateId);
            if ($spCandidate && !$spCandidate->isResolved()) {
                $this->spCandidateRepository->update($spCandidate, ['status' => 'cancelled_manual']);
            }
        }
    }
}
