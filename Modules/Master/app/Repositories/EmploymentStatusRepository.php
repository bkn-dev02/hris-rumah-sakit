<?php

namespace Modules\Master\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Master\Contracts\Repositories\EmploymentStatusRepositoryInterface;
use Modules\Master\Models\EmploymentStatus;

class EmploymentStatusRepository implements EmploymentStatusRepositoryInterface
{
    protected EmploymentStatus $model;

    public function __construct(EmploymentStatus $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->orderBy('name')->get();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->orderBy('name')->paginate($perPage);
    }

    public function findById(int $id): ?EmploymentStatus
    {
        return $this->model->find($id);
    }

    public function create(array $data): EmploymentStatus
    {
        return $this->model->create($data);
    }

    public function update(EmploymentStatus $employmentStatus, array $data): bool
    {
        return $employmentStatus->update($data);
    }

    public function delete(EmploymentStatus $employmentStatus): bool
    {
        return (bool) $employmentStatus->delete();
    }
}
