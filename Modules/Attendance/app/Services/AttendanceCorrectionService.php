<?php

namespace Modules\Attendance\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Attendance\Contracts\Repositories\AttendanceCorrectionRepositoryInterface;
use Modules\Attendance\Contracts\Services\AttendanceCorrectionServiceInterface;

class AttendanceCorrectionService implements AttendanceCorrectionServiceInterface
{
    public function __construct(
        protected AttendanceCorrectionRepositoryInterface $correctionRepository
    ) {}

    public function history(int $attendanceId): Collection
    {
        return $this->correctionRepository->historyByAttendance($attendanceId);
    }
}
