<?php

namespace Modules\Master\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Master\DTOs\EmployeePlacementData;
use Modules\Master\Models\EmployeePlacement;

interface EmployeePlacementServiceInterface
{
    public function history(int $employeeId): Collection;

    public function current(int $employeeId): ?EmployeePlacement;

    public function createPlacement(EmployeePlacementData $data): EmployeePlacement;
}
