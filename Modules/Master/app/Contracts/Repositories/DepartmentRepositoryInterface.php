<?php

namespace Modules\Master\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Master\Models\Department;

interface DepartmentRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function findById(int $id): ?Department;

    public function tree(): Collection;

    public function create(array $data): Department;

    public function update(Department $department, array $data): bool;

    public function delete(Department $department): bool;
}
