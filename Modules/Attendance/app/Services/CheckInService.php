<?php

namespace Modules\Attendance\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

use Modules\Attendance\Contracts\Repositories\CheckInRepositoryInterface;
use Modules\Attendance\Contracts\Services\CheckInServiceInterface;
use Modules\Attendance\Models\CheckIn;
use Modules\Master\Models\Employee;


class CheckInService implements CheckInServiceInterface
{
    public function __construct(protected CheckInRepositoryInterface $repository) {}

    public function findById(int $id): CheckIn
    {
        $checkIn = $this->repository->findById($id);

        if (! $checkIn) {
            throw new ModelNotFoundException('Check-in tidak ditemukan.');
        }

        return $checkIn;
    }

    public function create(array $data): CheckIn
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): CheckIn
    {
        $checkIn = $this->findById($id);

        $this->repository->update($checkIn, $data);

        return $checkIn->fresh();
    }

    public function delete(int $id): bool
    {
        $checkIn = $this->findById($id);

        return $this->repository->delete($checkIn);
    }

    public function findByEmployeeAndDate(int $employeeId, string $workDate): ?CheckIn
    {
        return $this->repository->findByEmployeeAndDate($employeeId, $workDate);
    }

    public function createEmergency(array $data): CheckIn
    {
        $data['type'] = 'emergency';
        $data['emergency_status'] = 'pending';
        $data['location_id'] = null;
        $data['distance_meters'] = null;

        return $this->repository->create($data);
    }

    public function decideEmergency(int $checkInId, Employee $hrApprover, bool $approve, ?string $note = null): CheckIn
    {
        $checkIn = $this->findById($checkInId);

        if ($checkIn->type !== 'emergency' || $checkIn->emergency_status !== 'pending') {
            throw new \RuntimeException('Presensi darurat tidak ditemukan atau sudah diproses.');
        }

        $this->repository->update($checkIn, [
            'emergency_status' => $approve ? 'approved' : 'rejected',
            'emergency_decided_by' => $hrApprover->id,
            'emergency_decided_at' => now(),
            'emergency_decision_note' => $note,
        ]);

        return $checkIn->fresh();
    }

    public function pendingEmergencies(): Collection
    {
        return CheckIn::query()
            ->pendingEmergency()
            ->with(['employee'])
            ->orderByDesc('checked_at')
            ->get();
    }

    public function hasUnseenEmergency(): bool
    {
        $latestPendingId = CheckIn::query()
            ->pendingEmergency()
            ->max('id');

        if (! $latestPendingId) {
            return false;
        }

        $lastSeenId = session('last_seen_emergency_id', 0);

        return $latestPendingId > $lastSeenId;
    }

    public function markEmergencySeen(): void
    {
        $latestPendingId = CheckIn::query()
            ->pendingEmergency()
            ->max('id');

        session(['last_seen_emergency_id' => $latestPendingId ?? session('last_seen_emergency_id', 0)]);
    }

    public function myEmergencyToday(int $employeeId): ?CheckIn
    {
        return $this->repository->findTodayByEmployeeAndType($employeeId, 'emergency');
    }

    public function findMyEmergency(int $id, int $employeeId): CheckIn
    {
        $checkIn = $this->repository->findById($id);

        if (! $checkIn || $checkIn->type !== 'emergency' || $checkIn->employee_id !== $employeeId) {
            throw new ModelNotFoundException('Presensi darurat tidak ditemukan.');
        }

        return $checkIn;
    }

    public function myEmergencyHistory(int $employeeId): Collection
    {
        return $this->repository->allByEmployeeAndType($employeeId, 'emergency');
    }
}
