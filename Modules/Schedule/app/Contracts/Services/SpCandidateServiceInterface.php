<?php

namespace Modules\Schedule\Contracts\Services;

use Carbon\Carbon;
use Modules\Schedule\Models\SpCandidate;

interface SpCandidateServiceInterface
{
    /**
     * Run the compliance check for one employee/date/shift, 30 minutes after shift start.
     * Applies the check order: holiday -> cuti -> emergency attendance -> manual confirmation.
     * Creates an SpCandidate if none of those apply and the employee hasn't checked in yet.
     * Called by the scheduled command, idempotent per (employee_id, date, shift_id).
     */
    public function runCheck(int $employeeId, Carbon $date, int $shiftId): ?SpCandidate;

    /**
     * Handle a late check-in for an employee who already has an open SpCandidate:
     * update status to pending_decision and notify HRD.
     */
    public function handleLateCheckin(int $employeeId, Carbon $date, int $shiftId, Carbon $checkedInAt): void;

    /**
     * HRD decision on a pending_decision candidate: issue SP or cancel it.
     */
    public function decide(int $spCandidateId, bool $issueSp, int $decidedByEmployeeId, ?string $note): SpCandidate;

    /**
     * Record a manual confirmation (before or after an SpCandidate exists) that cancels/prevents SP.
     */
    public function recordManualConfirmation(int $employeeId, Carbon $date, int $shiftId, string $note, int $confirmedByEmployeeId, ?int $spCandidateId = null): void;

    public function getForDepartment(int $departmentId, ?string $status = null);

    public function getAll(?string $status = null);

    public function find(int $id): ?SpCandidate;
}
