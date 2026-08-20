<?php

namespace Modules\Leave\Services;

use Carbon\Carbon;
use Modules\Attendance\Models\Attendance;
use Modules\Attendance\Models\AttendanceStatus;
use Modules\Leave\Models\Holiday;
use Modules\Leave\Models\LeaveRequest;
use RuntimeException;

class LeaveAttendanceService
{
    protected const ATTENDANCE_STATUS_CODE = 'CUTI';

    public function applyForApprovedLeaveRequest(LeaveRequest $leaveRequest): void
    {
        $attendanceStatus = AttendanceStatus::query()
            ->where('code', self::ATTENDANCE_STATUS_CODE)
            ->first();

        if (! $attendanceStatus) {
            throw new RuntimeException(
                'Status "CUTI" belum ditambahkan oleh admin di menu Status Kehadiran.'
            );
        }

        $employee = $leaveRequest->employee;
        $originalTotalDays = $leaveRequest->total_days;

        $start = Carbon::parse($leaveRequest->start_date)->startOfDay();
        $end = Carbon::parse($leaveRequest->end_date)->startOfDay();

        $holidays = Holiday::query()
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->pluck('date')
            ->map(fn($date) => $date->toDateString())
            ->all();

        $convertedDays = 0;
        $skippedDates = [];
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            $isWeekend = $cursor->isWeekend();
            $isHoliday = in_array($cursor->toDateString(), $holidays, true);

            if ($isWeekend || $isHoliday) {
                $cursor->addDay();
                continue;
            }

            $alreadyPresent = Attendance::query()
                ->where('employee_id', $employee->id)
                ->whereDate('work_date', $cursor->toDateString())
                ->exists();

            if ($alreadyPresent) {
                $skippedDates[] = $cursor->toDateString();
                $cursor->addDay();
                continue;
            }

            $shift = $employee->activeShiftFor($cursor->toDateString());

            Attendance::query()->create([
                'employee_id' => $employee->id,
                'work_date' => $cursor->toDateString(),
                'shift_id' => $shift?->id,
                'attendance_status_id' => $attendanceStatus->id,
                'determination_type' => 'auto',
                'source' => 'system',
                'notes' => "Otomatis dari pengajuan cuti #{$leaveRequest->id}",
            ]);

            $convertedDays++;
            $cursor->addDay();
        }

        if ($convertedDays !== $originalTotalDays) {
            $skippedNote = ! empty($skippedDates)
                ? 'Tanggal di-skip karena pegawai sudah tercatat hadir: ' . implode(', ', $skippedDates) . '.'
                : '';

            $leaveRequest->update([
                'total_days' => $convertedDays,
                'adjustment_note' => trim(
                    "Kuota disesuaikan dari {$originalTotalDays} menjadi {$convertedDays} hari. {$skippedNote}"
                ),
            ]);
        }
    }
}
