<?php

namespace Modules\Attendance\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Attendance\Contracts\Repositories\AttendanceLocationRepositoryInterface;
use Modules\Attendance\Models\AttendanceLocation;

class AttendanceLocationRepository implements AttendanceLocationRepositoryInterface
{
    protected AttendanceLocation $model;

    public function __construct(AttendanceLocation $model)
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

    public function findById(int $id): ?AttendanceLocation
    {
        return $this->model->find($id);
    }

    public function create(array $data): AttendanceLocation
    {
        return $this->model->create($data);
    }

    public function update(AttendanceLocation $location, array $data): bool
    {
        return $location->update($data);
    }

    public function delete(AttendanceLocation $location): bool
    {
        return (bool) $location->delete();
    }
}
