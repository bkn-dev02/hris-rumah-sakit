<?php

namespace Modules\Master\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Master\Contracts\Repositories\DepartmentRepositoryInterface;
use Modules\Master\Contracts\Services\DepartmentServiceInterface;
use Modules\Master\Models\Department;

class DepartmentService implements DepartmentServiceInterface
{
    public function __construct(
        protected DepartmentRepositoryInterface $departmentRepository
    ) {}

    public function getAll(): Collection
    {
        return $this->departmentRepository->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->departmentRepository->paginate($perPage);
    }

    public function tree(): Collection
    {
        return $this->departmentRepository->tree();
    }

    public function findById(int $id): Department
    {
        $department = $this->departmentRepository->findById($id);

        if (!$department) {
            throw new ModelNotFoundException('Department tidak ditemukan.');
        }

        return $department;
    }

    public function create(array $data): Department
    {
        return $this->departmentRepository->create($data);
    }

    public function update(int $id, array $data): Department
    {
        $department = $this->findById($id);

        $this->departmentRepository->update($department, $data);

        return $department->fresh();
    }

    public function delete(int $id): bool
    {
        $department = $this->findById($id);

        return $this->departmentRepository->delete($department);
    }
}
