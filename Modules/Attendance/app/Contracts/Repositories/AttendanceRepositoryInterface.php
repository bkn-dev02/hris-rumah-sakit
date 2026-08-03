<?php

namespace Modules\Attendance\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Attendance\Models\Attendance;

interface AttendanceRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function findById(int $id): ?Attendance;

    public function findByEmployeeAndDate(int $employeeId, string $workDate): ?Attendance;

    public function history(int $employeeId, ?string $startDate = null, ?string $endDate = null): Collection;

    public function incomplete(?string $beforeDate = null): Collection;

    public function unresolved(): Collection;

    public function create(array $data): Attendance;

    public function update(Attendance $attendance, array $data): bool;

    public function findOpenForEmployee(int $employeeId): ?Attendance;

    public function countCheckedInForDate(string $date): int;

    public function recentForDate(string $date, int $limit = 10): Collection;
}
