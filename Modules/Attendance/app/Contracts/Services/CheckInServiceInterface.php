<?php

namespace Modules\Attendance\Contracts\Services;

use Illuminate\Support\Collection;
use Modules\Attendance\Models\CheckIn;
use Modules\Master\Models\Employee;

interface CheckInServiceInterface
{
    public function findById(int $id): CheckIn;

    public function create(array $data): CheckIn;

    public function update(int $id, array $data): CheckIn;

    public function delete(int $id): bool;

    public function findByEmployeeAndDate(int $employeeId, string $workDate): ?CheckIn;

    public function createEmergency(array $data): CheckIn;

    public function decideEmergency(int $checkInId, Employee $hrApprover, bool $approve, ?string $note = null): CheckIn;

    public function pendingEmergencies(): Collection;

    public function hasUnseenEmergency(): bool;

    public function markEmergencySeen(): void;
}
