<?php

namespace Modules\Attendance\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Attendance\Contracts\Repositories\AttendanceCorrectionRepositoryInterface;
use Modules\Attendance\Models\AttendanceCorrection;

class AttendanceCorrectionRepository implements AttendanceCorrectionRepositoryInterface
{
    protected AttendanceCorrection $model;

    public function __construct(AttendanceCorrection $model)
    {
        $this->model = $model;
    }

    public function historyByAttendance(int $attendanceId): Collection
    {
        return $this->model
            ->with('newStatus', 'correctedBy')
            ->where('attendance_id', $attendanceId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function create(array $data): AttendanceCorrection
    {
        return $this->model->create($data);
    }
}
