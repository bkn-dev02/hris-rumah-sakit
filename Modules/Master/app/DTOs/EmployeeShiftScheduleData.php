<?php

namespace Modules\Master\DTOs;

use Carbon\Carbon;

readonly class EmployeeShiftScheduleData
{
    public function __construct(
        public int $employeeId,
        public int $shiftId,
        public Carbon $startDate,
        public ?string $notes = null,
        public ?int $createdBy = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            employeeId: $data['employee_id'],
            shiftId: $data['shift_id'],
            startDate: Carbon::parse($data['start_date']),
            notes: $data['notes'] ?? null,
            createdBy: $data['created_by'] ?? null,
        );
    }
}
