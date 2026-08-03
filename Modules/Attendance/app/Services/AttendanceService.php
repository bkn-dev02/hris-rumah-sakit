<?php

namespace Modules\Attendance\Services;

use Carbon\Carbon;
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
use Modules\Attendance\DTOs\CheckInData;
use Modules\Attendance\DTOs\CheckOutData;
use Modules\Attendance\Exceptions\AttendanceException;
use Modules\Attendance\Models\Attendance;
use Modules\Master\Contracts\Services\EmployeeShiftScheduleServiceInterface;
use Modules\Master\Contracts\Services\EmployeeServiceInterface;

class AttendanceService implements AttendanceServiceInterface
{

    protected const DEFAULT_LATE_TOLERANCE_MINUTES = 15;
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

    public function checkIn(CheckInData $data): Attendance
    {
        return DB::transaction(function () use ($data) {

            if ($this->attendanceRepository->findOpenForEmployee($data->employeeId)) {
                throw new AttendanceException('Anda masih memiliki check-in yang belum checkout. Silakan checkout terlebih dahulu.');
            }

            $schedule = $this->shiftScheduleService->current($data->employeeId);

            if (!$schedule) {
                throw new AttendanceException('Anda belum memiliki jadwal shift aktif. Hubungi admin.');
            }

            $workDate = Carbon::today();

            if ($this->attendanceRepository->findByEmployeeAndDate($data->employeeId, $workDate->toDateString())) {
                throw new AttendanceException('Anda sudah melakukan absensi hari ini.');
            }

            $location = $this->locationRepository
                ->activeList()
                ->first(fn($loc) => $loc->isWithinRadius($data->latitude, $data->longitude));

            if (!$location) {
                throw new AttendanceException('Lokasi Anda berada di luar radius area kerja yang diizinkan.');
            }

            return $this->attendanceRepository->create([
                'employee_id' => $data->employeeId,
                'work_date' => $workDate,
                'shift_id' => $schedule->shift_id,
                'check_in_at' => now(),
                'check_in_latitude' => $data->latitude,
                'check_in_longitude' => $data->longitude,
                'check_in_photo' => $data->photoPath,
                'check_in_location_id' => $location->id,
                'check_in_distance_meters' => $location->distanceTo($data->latitude, $data->longitude),
                'source' => 'mobile',
            ]);
        });
    }

    public function checkOut(CheckOutData $data): Attendance
    {
        return DB::transaction(function () use ($data) {

            $attendance = $this->attendanceRepository->findOpenForEmployee($data->employeeId);

            if (!$attendance) {
                throw new AttendanceException('Anda belum melakukan check-in.');
            }

            $location = $this->locationRepository
                ->activeList()
                ->first(fn($loc) => $loc->isWithinRadius($data->latitude, $data->longitude));

            if (!$location) {
                throw new AttendanceException('Lokasi Anda berada di luar radius area kerja yang diizinkan.');
            }

            $this->attendanceRepository->update($attendance, [
                'check_out_at'               => now(),
                'check_out_latitude'         => $data->latitude,
                'check_out_longitude'        => $data->longitude,
                'check_out_photo'            => $data->photoPath,
                'check_out_location_id'      => $location->id,
                'check_out_distance_meters'  => $location->distanceTo($data->latitude, $data->longitude),
            ]);

            $attendance = $attendance->fresh(['shift', 'employee']);

            $this->resolveStatusFor($attendance);

            return $attendance->fresh();
        });
    }

    public function todayFor(int $employeeId): ?Attendance
    {
        return $this->attendanceRepository->findOpenForEmployee($employeeId)
            ?? $this->attendanceRepository->findByEmployeeAndDate($employeeId, Carbon::today()->toDateString());
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
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

        $shiftStart = Carbon::parse("{$workDate} {$shift->start_time}");
        $shiftEnd = Carbon::parse("{$workDate} {$shift->end_time}");

        if ($shiftEnd->lessThanOrEqualTo($shiftStart)) {
            $shiftEnd->addDay();
        }

        $lateThreshold = $shiftStart->copy()->addMinutes(self::DEFAULT_LATE_TOLERANCE_MINUTES);
        $earlyLeaveThreshold = $shiftEnd->copy()->subMinutes(self::DEFAULT_EARLY_LEAVE_TOLERANCE_MINUTES);

        $isLate = $attendance->check_in_at->greaterThan($lateThreshold);
        $isEarlyLeave = $attendance->check_out_at->lessThan($earlyLeaveThreshold);

        $workedMinutes = $attendance->check_in_at->diffInMinutes($attendance->check_out_at);
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

    public function todaySummary(): array
    {
        $today = Carbon::today()->toDateString();

        $totalEmployees = $this->employeeService->getAll()->count();
        $present = $this->attendanceRepository->countCheckedInForDate($today);
        $onLeave = $this->exceptionRepository->countApprovedForDate($today);
        $absent = max($totalEmployees - $present - $onLeave, 0);

        return [
            'total_employees' => $totalEmployees,
            'present'          => $present,
            'on_leave'         => $onLeave,
            'absent'           => $absent,
        ];
    }

    public function recentToday(int $limit = 10): Collection
    {
        return $this->attendanceRepository->recentForDate(Carbon::today()->toDateString(), $limit);
    }
}
