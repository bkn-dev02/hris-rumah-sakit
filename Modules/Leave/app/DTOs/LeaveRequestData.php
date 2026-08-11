<?php

namespace Modules\Leave\DTOs;

class LeaveRequestData
{
    public function __construct(
        public readonly int $employeeId,
        public readonly int $leaveTypeId,
        public readonly string $startDate,
        public readonly string $endDate,
        public readonly string $reason,
        public readonly ?string $attachment = null,
    ) {}
}
