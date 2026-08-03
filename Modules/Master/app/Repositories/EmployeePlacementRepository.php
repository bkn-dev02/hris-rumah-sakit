<?php

namespace Modules\Master\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Master\Contracts\Repositories\EmployeePlacementRepositoryInterface;
use Modules\Master\Models\EmployeePlacement;

class EmployeePlacementRepository implements EmployeePlacementRepositoryInterface
{
    protected EmployeePlacement $model;

    public function __construct(EmployeePlacement $model)
    {
        $this->model = $model;
    }

    public function historyByEmployee(int $employeeId): Collection
    {
        return $this->model
            ->with('department', 'position', 'createdBy')
            ->where('employee_id', $employeeId)
            ->orderByDesc('start_date')
            ->get();
    }

    public function findActiveByEmployee(int $employeeId): ?EmployeePlacement
    {
        return $this->model
            ->with('department', 'position')
            ->where('employee_id', $employeeId)
            ->whereNull('end_date')
            ->latest('start_date')
            ->first();
    }

    public function findById(int $id): ?EmployeePlacement
    {
        return $this->model->with('department', 'position')->find($id);
    }

    public function create(array $data): EmployeePlacement
    {
        return $this->model->create($data);
    }

    public function update(EmployeePlacement $placement, array $data): bool
    {
        return $placement->update($data);
    }
}
