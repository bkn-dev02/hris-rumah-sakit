<?php

namespace Modules\Master\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Master\Contracts\Repositories\DepartmentRepositoryInterface;
use Modules\Master\Models\Department;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    protected Department $model;

    public function __construct(Department $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->orderBy('name')->get();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->with('parent')
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Department
    {
        return $this->model->find($id);
    }

    public function tree(): Collection
    {
        $departments = $this->model->orderBy('name')->get();

        return $this->buildTree($departments);
    }

    public function create(array $data): Department
    {
        return $this->model->create($data);
    }

    public function update(Department $department, array $data): bool
    {
        return $department->update($data);
    }

    public function delete(Department $department): bool
    {
        return (bool) $department->delete();
    }

    protected function buildTree(Collection $departments, ?int $parentId = null): Collection
    {
        return $departments
            ->where('parent_id', $parentId)
            ->map(function ($department) use ($departments) {
                $department->setRelation('children', $this->buildTree($departments, $department->id));

                return $department;
            })
            ->values();
    }
}
