<?php

namespace Modules\Attendance\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Attendance\Contracts\Repositories\AttendanceStatusRepositoryInterface;
use Modules\Attendance\Models\AttendanceStatus;

class AttendanceStatusRepository implements AttendanceStatusRepositoryInterface
{
    protected AttendanceStatus $model;

    public function __construct(AttendanceStatus $model)
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

    public function activeList(): Collection
    {
        return $this->model->active()->orderBy('name')->get();
    }

    public function findById(int $id): ?AttendanceStatus
    {
        return $this->model->find($id);
    }

    public function findByCode(string $code): ?AttendanceStatus
    {
        return $this->model->where('code', $code)->first();
    }

    public function autoDetermined(): Collection
    {
        return $this->model->active()->autoDetermined()->get();
    }

    public function create(array $data): AttendanceStatus
    {
        return $this->model->create($data);
    }

    public function update(AttendanceStatus $status, array $data): bool
    {
        return $status->update($data);
    }

    public function delete(AttendanceStatus $status): bool
    {
        return (bool) $status->delete();
    }
}
