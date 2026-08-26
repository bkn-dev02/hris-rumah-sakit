<?php

namespace Modules\Schedule\Contracts\Repositories;

use Carbon\Carbon;
use Modules\Schedule\Models\SpCandidate;

interface SpCandidateRepositoryInterface
{
    public function find(int $id): ?SpCandidate;

    public function findByEmployeeDateShift(int $employeeId, Carbon $date, int $shiftId): ?SpCandidate;

    public function create(array $data): SpCandidate;

    public function update(SpCandidate $spCandidate, array $data): SpCandidate;

    /**
     * Get candidates scoped for a department (for Kepala Ruangan notifications/listing).
     */
    public function getForDepartment(int $departmentId, ?string $status = null);

    /**
     * Get all candidates (for HRD/Direktur listing), optionally filtered by status.
     */
    public function getAll(?string $status = null);

    public function getPendingDecisions();
}
