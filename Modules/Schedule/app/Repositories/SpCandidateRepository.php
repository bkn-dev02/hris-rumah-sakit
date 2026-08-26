<?php

namespace Modules\Schedule\Repositories;

use Carbon\Carbon;
use Modules\Schedule\Contracts\Repositories\SpCandidateRepositoryInterface;
use Modules\Schedule\Models\SpCandidate;

class SpCandidateRepository implements SpCandidateRepositoryInterface
{
    public function find(int $id): ?SpCandidate
    {
        return SpCandidate::find($id);
    }

    public function findByEmployeeDateShift(int $employeeId, Carbon $date, int $shiftId): ?SpCandidate
    {
        return SpCandidate::where('employee_id', $employeeId)
            ->whereDate('date', $date)
            ->where('shift_id', $shiftId)
            ->first();
    }

    public function create(array $data): SpCandidate
    {
        return SpCandidate::create($data);
    }

    public function update(SpCandidate $spCandidate, array $data): SpCandidate
    {
        $spCandidate->update($data);

        return $spCandidate->fresh();
    }

    public function getForDepartment(int $departmentId, ?string $status = null)
    {
        return SpCandidate::where('department_id', $departmentId)
            ->when($status, fn($query) => $query->where('status', $status))
            ->with(['employee', 'shift'])
            ->latest('detected_at')
            ->get();
    }

    public function getAll(?string $status = null)
    {
        return SpCandidate::when($status, fn($query) => $query->where('status', $status))
            ->with(['employee', 'shift', 'department'])
            ->latest('detected_at')
            ->get();
    }

    public function getPendingDecisions()
    {
        return SpCandidate::where('status', 'pending_decision')
            ->with(['employee', 'shift', 'department'])
            ->latest('detected_at')
            ->get();
    }
}
