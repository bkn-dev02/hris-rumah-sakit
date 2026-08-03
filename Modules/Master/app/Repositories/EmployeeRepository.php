<?php

namespace Modules\Master\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Master\Contracts\Repositories\EmployeeRepositoryInterface;
use Modules\Master\Models\Employee;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    protected Employee $model;

    public function __construct(Employee $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->with('employmentStatus')->orderBy('name')->get();
    }

    public function paginate(int $perPage = 10, bool $trashed = false): LengthAwarePaginator
    {
        return $this->model
            ->with('employmentStatus', 'user')
            ->when($trashed, fn($query) => $query->onlyTrashed())
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function findBySlug(string $slug, bool $withTrashed = false): ?Employee
    {
        return $this->model
            ->with('employmentStatus', 'user')
            ->when($withTrashed, fn($query) => $query->withTrashed())
            ->where('slug', $slug)
            ->first();
    }

    public function findByUserId(int $userId): ?Employee
    {
        return $this->model->where('user_id', $userId)->first();
    }

    public function findByEmployeeNumber(string $employeeNumber): ?Employee
    {
        return $this->model->where('employee_number', $employeeNumber)->first();
    }

    public function create(array $data): Employee
    {
        return $this->model->create($data);
    }

    public function update(Employee $employee, array $data): bool
    {
        return $employee->update($data);
    }

    public function delete(Employee $employee): bool
    {
        return (bool) $employee->delete();
    }

    public function restore(string $slug): bool
    {
        $employee = $this->model->onlyTrashed()->where('slug', $slug)->first();

        if (!$employee) {
            return false;
        }

        return (bool) $employee->restore();
    }

    public function forceDelete(Employee $employee): bool
    {
        return (bool) $employee->forceDelete();
    }
}
