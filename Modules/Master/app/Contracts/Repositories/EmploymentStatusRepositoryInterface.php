<?php

namespace Modules\Master\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Master\Models\EmploymentStatus;

interface EmploymentStatusRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function findById(int $id): ?EmploymentStatus;

    public function create(array $data): EmploymentStatus;

    public function update(EmploymentStatus $employmentStatus, array $data): bool;

    public function delete(EmploymentStatus $employmentStatus): bool;
}
