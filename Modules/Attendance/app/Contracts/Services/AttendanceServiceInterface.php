<?php

namespace Modules\Attendance\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Attendance\DTOs\CheckInData;
use Modules\Attendance\DTOs\CheckOutData;
use Modules\Attendance\Models\Attendance;

interface AttendanceServiceInterface
{

    public function checkIn(CheckInData $data): Attendance;

    public function checkOut(CheckOutData $data): Attendance;

    public function todayFor(int $employeeId): ?Attendance;

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function findById(int $id): Attendance;

    public function history(int $employeeId, ?string $startDate = null, ?string $endDate = null): Collection;

    public function correctStatus(int $attendanceId, int $newStatusId, string $reason, int $correctedBy): Attendance;

    public function resolveCompletedStatuses(): int;

    public function flagForgottenCheckouts(string $beforeDate): int;

    public function todaySummary(): array;

    public function recentToday(int $limit = 10): Collection;
}
