<?php

namespace Modules\Attendance\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Modules\Attendance\Contracts\Repositories\AttendanceCorrectionRepositoryInterface;
use Modules\Attendance\Contracts\Repositories\AttendanceExceptionRequestRepositoryInterface;
use Modules\Attendance\Contracts\Repositories\AttendanceLocationRepositoryInterface;
use Modules\Attendance\Contracts\Repositories\AttendanceRepositoryInterface;
use Modules\Attendance\Contracts\Repositories\AttendanceStatusRepositoryInterface;
use Modules\Attendance\Contracts\Services\AttendanceServiceInterface;
use Modules\Attendance\Models\Attendance;
use Modules\Master\Contracts\Services\EmployeeShiftScheduleServiceInterface;
use Modules\Master\Contracts\Services\EmployeeServiceInterface;
use Modules\Attendance\Exceptions\AttendanceException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Modules\Schedule\Contracts\Services\SpCandidateServiceInterface;
use Illuminate\Support\Facades\Log;
use Modules\Schedule\Contracts\Services\ScheduleServiceInterface;

class AttendanceService implements AttendanceServiceInterface
{

    protected const DEFAULT_LATE_TOLERANCE_MINUTES = 0;
    protected const DEFAULT_EARLY_LEAVE_TOLERANCE_MINUTES = 15;

    public function __construct(
        protected AttendanceRepositoryInterface $attendanceRepository,
        protected AttendanceLocationRepositoryInterface $locationRepository,
        protected AttendanceStatusRepositoryInterface $statusRepository,
        protected AttendanceExceptionRequestRepositoryInterface $exceptionRepository,
        protected AttendanceCorrectionRepositoryInterface $correctionRepository,
        protected EmployeeShiftScheduleServiceInterface $shiftScheduleService,
        protected EmployeeServiceInterface $employeeService,
    ) {}

    public function checkIn(int $employeeId, int $checkInId): Attendance
    {
        return DB::transaction(function () use ($employeeId, $checkInId) {

            $workDate = Carbon::today();

            if ($this->attendanceRepository->findByEmployeeAndDate($employeeId, $workDate->toDateString())) {
                throw new AttendanceException('Data absensi untuk hari ini sudah ada.');
            }

            $resolved = app(ScheduleServiceInterface::class)
                ->resolveEffectiveShift($employeeId, $workDate);

            $schedule = $this->shiftScheduleService->current($employeeId);

            if (!$resolved['shift_id']) {
                throw new AttendanceException('Anda belum memiliki jadwal shift aktif. Hubungi admin.');
            }

            $attendance = $this->attendanceRepository->create([
                'employee_id' => $employeeId,
                'work_date' => $workDate,
                'shift_id' => $schedule->shift_id,
                'check_in_id' => $checkInId,
                'source' => 'mobile',
            ]);

            Cache::forget("attendance:summary:{$workDate->toDateString()}");
            Cache::forget("attendance:recent:{$workDate->toDateString()}:10");

            $this->notifyScheduleModuleOfLateCheckin($employeeId, $workDate, $schedule->shift_id);

            return $attendance;
        });
    }

    protected function notifyScheduleModuleOfLateCheckin(int $employeeId, Carbon $workDate, int $shiftId): void
    {
        try {
            app(SpCandidateServiceInterface::class)
                ->handleLateCheckin($employeeId, $workDate, $shiftId, Carbon::now());
        } catch (\Throwable $e) {
            Log::warning(
                "Gagal sync SP Candidate saat check-in pegawai {$employeeId}: {$e->getMessage()}"
            );
        }
    }

    public function checkOut(int $employeeId, int $checkOutId): Attendance
    {
        return DB::transaction(function () use ($employeeId, $checkOutId) {

            $attendance = $this->attendanceRepository->findOpenForEmployee($employeeId);

            if (!$attendance) {
                throw new AttendanceException('Anda belum melakukan check-in hari ini.');
            }

            $this->attendanceRepository->update($attendance, [
                'check_out_id' => $checkOutId,
            ]);

            $attendance = $attendance->fresh(['shift', 'checkIn', 'checkOut', 'employee']);

            $this->resolveStatusFor($attendance);

            $workDate = $attendance->work_date->toDateString();
            Cache::forget("attendance:summary:{$workDate}");
            Cache::forget("attendance:recent:{$workDate}:10");

            return $attendance->fresh();
        });
    }

    public function todayFor(int $employeeId): ?Attendance
    {
        return $this->attendanceRepository->findOpenForEmployee($employeeId)
            ?? $this->attendanceRepository->findByEmployeeAndDate($employeeId, Carbon::today()->toDateString());
    }

    public function recentTodayForDisplay(int $limit = 10): array
    {
        $today = Carbon::today()->toDateString();

        return Cache::remember("attendance:recent:{$today}:{$limit}", now()->addMinutes(10), function () use ($limit) {
            return $this->recentToday($limit)
                ->map(fn($attendance) => $this->toDisplayArray($attendance))
                ->all();
        });
    }

    protected function toDisplayArray(Attendance $attendance): array
    {
        [$badgeLabel, $badgeColor] = match (true) {
            $attendance->status?->code === 'CUTI' => [$attendance->status->name, 'sky'],
            is_null($attendance->check_out_id) => ['Belum Check Out', 'amber'],
            is_null($attendance->attendance_status_id) => ['Perlu Review', 'slate'],
            $attendance->status->code === 'TERLAMBAT' => [$attendance->status->name, 'rose'],
            default => [$attendance->status->name, 'emerald'],
        };

        return [
            'id' => $attendance->id,
            'work_date' => $attendance->work_date->translatedFormat('d M Y'),
            'employee_name' => $attendance->employee->name ?? '-',
            'employment_status_name' => $attendance->employee->employmentStatus->name ?? '-',
            'employee_position_name' => $attendance->employee->currentPosition()?->name ?? '-',
            'check_in_time' => $attendance->checkIn?->checked_at?->format('H:i') ?? '-',
            'check_in_photo_url'      => $attendance->checkIn?->photo
                ? asset('storage/' . $attendance->checkIn->photo)
                : null,
            'check_out_time' => $attendance->checkOut?->checked_at?->format('H:i'),
            'check_out_photo_url'      => $attendance->checkOut?->photo
                ? asset('storage/' . $attendance->checkOut->photo)
                : null,
            'shift_name' => $attendance->shift->name ?? '-',
            'start_time' => $attendance->shift?->start_time
                ? Carbon::parse($attendance->shift->start_time)->format('H:i')
                : null,
            'end_time' => $attendance->shift?->end_time
                ? Carbon::parse($attendance->shift->end_time)->format('H:i')
                : null,
            'badge_label' => $badgeLabel,
            'badge_color' => $badgeColor,
        ];
    }

    public function paginateForDisplay(int $perPage = 25, array $filters = []): LengthAwarePaginator
    {
        $today = Carbon::today()->toDateString();

        $filters['start_date'] = $filters['start_date'] ?? $today;
        $filters['end_date']   = $filters['end_date'] ?? $today;

        return $this->attendanceRepository
            ->paginate($perPage, $filters)
            ->through(fn(Attendance $attendance) => $this->toDisplayArray($attendance));
    }

    public function paginate(int $perPage = 25, array $filters = []): LengthAwarePaginator
    {
        return $this->attendanceRepository->paginate($perPage, $filters);
    }

    public function findById(int $id): Attendance
    {
        $attendance = $this->attendanceRepository->findById($id);

        if (!$attendance) {
            throw new ModelNotFoundException('Data absensi tidak ditemukan.');
        }

        return $attendance;
    }

    public function history(int $employeeId, ?string $startDate = null, ?string $endDate = null): Collection
    {
        return $this->attendanceRepository->history($employeeId, $startDate, $endDate);
    }

    public function correctStatus(int $attendanceId, int $newStatusId, string $reason, int $correctedBy): Attendance
    {
        return DB::transaction(function () use ($attendanceId, $newStatusId, $reason, $correctedBy) {

            $attendance = $this->findById($attendanceId);

            $this->correctionRepository->create([
                'attendance_id'       => $attendance->id,
                'previous_status_id'  => $attendance->attendance_status_id,
                'new_status_id'       => $newStatusId,
                'reason'              => $reason,
                'corrected_by'        => $correctedBy,
            ]);

            $this->attendanceRepository->update($attendance, [
                'attendance_status_id'  => $newStatusId,
                'determination_type'    => 'manual',
            ]);

            return $attendance->fresh();
        });
    }

    public function resolveCompletedStatuses(): int
    {
        $attendances = $this->attendanceRepository->unresolved();

        foreach ($attendances as $attendance) {
            $this->resolveStatusFor($attendance);
        }

        return $attendances->count();
    }

    public function flagForgottenCheckouts(string $beforeDate): int
    {
        $attendances = $this->attendanceRepository->incomplete($beforeDate);

        $forgottenStatus = $this->statusRepository->findByCode('LUPA_CHECKOUT');

        foreach ($attendances as $attendance) {

            $approvedException = $this->exceptionRepository->findApprovedForDate(
                $attendance->employee_id,
                $attendance->work_date->toDateString()
            );

            if ($approvedException) {
                $this->attendanceRepository->update($attendance, [
                    'attendance_status_id'  => $approvedException->attendance_status_id,
                    'determination_type'    => 'manual',
                ]);

                continue;
            }

            if ($forgottenStatus) {
                $this->attendanceRepository->update($attendance, [
                    'attendance_status_id'  => $forgottenStatus->id,
                    'determination_type'    => 'auto',
                ]);
            }
        }

        return $attendances->count();
    }

    protected function resolveStatusFor(Attendance $attendance): void
    {
        if (!$attendance->isComplete()) {
            return;
        }

        $approvedException = $this->exceptionRepository->findApprovedForDate(
            $attendance->employee_id,
            $attendance->work_date->toDateString()
        );

        if ($approvedException) {
            $this->attendanceRepository->update($attendance, [
                'attendance_status_id'  => $approvedException->attendance_status_id,
                'determination_type'    => 'manual',
            ]);

            return;
        }

        $shift = $attendance->shift;
        $workDate = $attendance->work_date->toDateString();

        $checkInTime = $attendance->checkIn->checked_at;
        $checkOutTime = $attendance->checkOut->checked_at;

        [$shiftStart, $shiftEnd] = $this->shiftWindow($shift, $workDate);

        $lateThreshold = $shiftStart->copy()->addMinutes(self::DEFAULT_LATE_TOLERANCE_MINUTES);
        $earlyLeaveThreshold = $shiftEnd->copy()->subMinutes(self::DEFAULT_EARLY_LEAVE_TOLERANCE_MINUTES);

        $isLate = $checkInTime->greaterThan($lateThreshold);
        $isEarlyLeave = $checkOutTime->lessThan($earlyLeaveThreshold);

        $workedMinutes = $checkInTime->diffInMinutes($checkOutTime);
        $shiftDurationMinutes = $shiftStart->diffInMinutes($shiftEnd);

        if ($workedMinutes < ($shiftDurationMinutes * 0.5)) {
            return;
        }

        $code = match (true) {
            $isLate => 'TERLAMBAT',
            $isEarlyLeave => 'PULANG_CEPAT',
            default => 'HADIR',
        };

        $status = $this->statusRepository->findByCode($code);

        if (!$status) {
            return;
        }

        $this->attendanceRepository->update($attendance, [
            'attendance_status_id'  => $status->id,
            'determination_type'    => 'auto',
        ]);
    }

    protected function shiftWindow($shift, string $workDate): array
    {
        $shiftStart = Carbon::parse("{$workDate} {$shift->start_time->format('H:i')}");
        $shiftEnd = Carbon::parse("{$workDate} {$shift->end_time->format('H:i')}");

        if ($shiftEnd->lessThanOrEqualTo($shiftStart)) {
            $shiftEnd->addDay();
        }

        return [$shiftStart, $shiftEnd];
    }

    public function todaySummary(): array
    {
        $today = Carbon::today()->toDateString();

        return Cache::remember("attendance_summary:{$today}", now()->addMinutes(10), function () use ($today) {
            $statusCounts = $this->employeeService->getEmployeeStatusCounts();
            $totalEmployees = $statusCounts['total'];
            $activeEmployees =  $statusCounts['active'];
            $inactiveEmployees = $statusCounts['inactive'];
            $present = $this->attendanceRepository->countCheckedInForDate($today);
            $onLeave = $this->exceptionRepository->countApprovedForDate($today);
            $absent = max($totalEmployees - $present - $onLeave, 0);

            return [
                'total' => $totalEmployees,
                'aktif' => $activeEmployees,
                'nonaktif' => $inactiveEmployees,
                'present' => $present,
                'on_leave' => $onLeave,
                'absent' => $absent,
            ];
        });
    }

    public function recentToday(int $limit = 10): Collection
    {
        return $this->attendanceRepository->recentForDate(Carbon::today()->toDateString(), $limit);
    }

    public function todayForDisplay(int $employeeId): ?array
    {
        $attendance = $this->attendanceRepository->findByEmployeeAndDate(
            $employeeId,
            Carbon::today()->toDateString()
        );

        return $attendance ? $this->toMobileArray($attendance) : null;
    }

    public function historyForDisplay(int $employeeId, ?string $startDate = null, ?string $endDate = null): array
    {
        $startDate = $startDate ?? Carbon::today()->subDays(30)->toDateString();
        $endDate = $endDate ?? Carbon::today()->toDateString();

        return $this->attendanceRepository
            ->history($employeeId, $startDate, $endDate)
            ->map(fn(Attendance $attendance) => $this->toMobileArray($attendance))
            ->all();
    }

    protected function toMobileArray(Attendance $attendance): array
    {
        $status = match (true) {
            is_null($attendance->check_in_id) => 'not_checked_in',
            is_null($attendance->check_out_id) => 'checked_in',
            default => 'checked_out',
        };

        return [
            'status'              => $status,
            'work_date'           => $attendance->work_date->toDateString(),
            'shift_name'          => $attendance->shift->name ?? null,
            'check_in_time'       => $attendance->checkIn?->checked_at?->format('H:i'),
            'check_in_photo_url'  => $attendance->checkIn?->photo ? asset('storage/' . $attendance->checkIn->photo) : null,
            'check_out_time'      => $attendance->checkOut?->checked_at?->format('H:i'),
            'check_out_photo_url' => $attendance->checkOut?->photo ? asset('storage/' . $attendance->checkOut->photo) : null,
            'attendance_status'   => $attendance->status->name ?? null,
        ];
    }

    public function getCheckInTimesForEmployeesToday(array $employeeIds): array
    {
        return $this->attendanceRepository->getCheckInTimesForEmployeesToday($employeeIds);
    }
}
