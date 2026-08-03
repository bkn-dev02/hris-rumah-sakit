<?php

namespace Modules\Master\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Master\Models\EmployeePlacement;

interface EmployeePlacementRepositoryInterface
{
    public function historyByEmployee(int $employeeId): Collection;

    public function findActiveByEmployee(int $employeeId): ?EmployeePlacement;

    public function findById(int $id): ?EmployeePlacement;

    public function create(array $data): EmployeePlacement;

    public function update(EmployeePlacement $placement, array $data): bool;
}
