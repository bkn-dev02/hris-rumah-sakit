<?php

namespace Modules\Attendance\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;

interface AttendanceCorrectionServiceInterface
{
    public function history(int $attendanceId): Collection;
}
