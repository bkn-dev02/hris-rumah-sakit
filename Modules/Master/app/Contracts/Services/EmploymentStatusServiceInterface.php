<?php

namespace Modules\Master\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Master\Models\EmploymentStatus;

interface EmploymentStatusServiceInterface
{
    public function getAll(): Collection;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function findById(int $id): EmploymentStatus;

    public function create(array $data): EmploymentStatus;

    public function update(int $id, array $data): EmploymentStatus;

    public function delete(int $id): bool;
}
