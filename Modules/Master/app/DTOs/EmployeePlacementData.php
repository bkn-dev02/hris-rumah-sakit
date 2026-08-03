<?php

namespace Modules\Master\DTOs;

use Carbon\Carbon;

readonly class EmployeePlacementData
{
    public function __construct(
        public int $employeeId,
        public int $departmentId,
        public int $positionId,
        public Carbon $startDate,
        public bool $isTemporary = false,
        public ?string $notes = null,
        public ?int $createdBy = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            employeeId: $data['employee_id'],
            departmentId: $data['department_id'],
            positionId: $data['position_id'],
            startDate: Carbon::parse($data['start_date']),
            isTemporary: $data['is_temporary'] ?? false,
            notes: $data['notes'] ?? null,
            createdBy: $data['created_by'] ?? null,
        );
    }
}
