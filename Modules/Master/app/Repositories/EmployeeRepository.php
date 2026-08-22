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

    public function getActiveInactiveCounts(): array
    {
        $active = $this->model->withTrashed()->where('is_active', true)->count();
        $inactive = $this->model->withTrashed()->where('is_active', false)->count();
        $total = $active + $inactive;

        return [
            'active' => $active,
            'inactive' => $inactive,
            'total' => $total,
        ];
    }

    public function paginate(int $perPage = 10, bool $trashed = false): LengthAwarePaginator
    {
        return $this->model
            ->with(['employmentStatus', 'user', 'placements.position'])
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
        $employee->update(['is_active' => false]);

        return (bool) $employee->delete();
    }

    public function restore(string $slug): bool
    {
        $employee = $this->model->onlyTrashed()->where('slug', $slug)->first();

        if (!$employee) {
            return false;
        }

        $restored = (bool) $employee->restore();

        if ($restored) {
            $employee->update(['is_active' => true]);
        }

        return $restored;
    }

    public function forceDelete(Employee $employee): bool
    {
        return (bool) $employee->forceDelete();
    }

    public function getDepartmentDistribution(): array
    {
        $employees = $this->model->where('is_active', true)->get();
        $total = $employees->count();

        $grouped = $employees->groupBy(
            fn($employee) => $employee->currentDepartment()?->name ?? 'Belum Ditempatkan'
        );

        return $grouped->map(fn($group, $name) => ['name' => $name, 'total' => $group->count(), 'percent' => $total > 0 ? round(($group->count() / $total) * 100) . '%' : '0%',])
            ->sortByDesc('total')
            ->values()
            ->all();
    }
}
