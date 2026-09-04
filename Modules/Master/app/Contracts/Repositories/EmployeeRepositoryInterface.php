<?php

namespace Modules\Master\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Master\Models\Employee;

interface EmployeeRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 10, bool $trashed = false, ?array $departmentIds = null): LengthAwarePaginator;

    public function findBySlug(string $slug, bool $withTrashed = false): ?Employee;

    public function findByUserId(int $userId): ?Employee;

    public function findByEmployeeNumber(string $employeeNumber): ?Employee;

    public function create(array $data): Employee;

    public function update(Employee $employee, array $data): bool;

    public function delete(Employee $employee): bool;

    public function restore(string $slug): bool;

    public function forceDelete(Employee $employee): bool;

    public function getDepartmentDistribution(): array;
}
