<?php

namespace Modules\Attendance\DTOs;

use Carbon\Carbon;

readonly class AttendanceExceptionRequestData
{
    public function __construct(
        public int $employeeId,
        public Carbon $workDate,
        public int $attendanceStatusId,
        public string $reason,
        public ?string $attachmentPath = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            employeeId: $data['employee_id'],
            workDate: Carbon::parse($data['work_date']),
            attendanceStatusId: $data['attendance_status_id'],
            reason: $data['reason'],
            attachmentPath: $data['attachment_path'] ?? null,
        );
    }
}
