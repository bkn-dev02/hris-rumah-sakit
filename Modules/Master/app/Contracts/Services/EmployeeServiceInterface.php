<?php

namespace Modules\Master\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Master\DTOs\EmployeeData;
use Modules\Master\Models\Employee;

interface EmployeeServiceInterface
{
    public function getAll(): Collection;

    public function paginate(int $perPage = 10, bool $trashed = false): LengthAwarePaginator;

    public function findBySlug(string $slug, bool $withTrashed = false): Employee;

    public function create(EmployeeData $data, ?string $photo = null): Employee;

    public function update(string $slug, EmployeeData $data, ?string $photo = null): Employee;

    public function delete(string $slug): bool;

    public function restore(string $slug): bool;

    public function forceDelete(string $slug): bool;
}
