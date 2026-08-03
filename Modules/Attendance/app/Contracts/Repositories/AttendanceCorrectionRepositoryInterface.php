<?php

namespace Modules\Attendance\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Attendance\Models\AttendanceCorrection;

interface AttendanceCorrectionRepositoryInterface
{
    public function historyByAttendance(int $attendanceId): Collection;

    public function create(array $data): AttendanceCorrection;
}
