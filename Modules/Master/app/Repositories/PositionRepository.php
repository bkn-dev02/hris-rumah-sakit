<?php

namespace Modules\Master\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Master\Contracts\Repositories\PositionRepositoryInterface;
use Modules\Master\Models\Position;

class PositionRepository implements PositionRepositoryInterface
{
    protected Position $model;

    public function __construct(Position $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->orderBy('level')->get();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->orderBy('level')->paginate($perPage);
    }

    public function findById(int $id): ?Position
    {
        return $this->model->find($id);
    }

    public function create(array $data): Position
    {
        return $this->model->create($data);
    }

    public function update(Position $position, array $data): bool
    {
        return $position->update($data);
    }

    public function delete(Position $position): bool
    {
        return (bool) $position->delete();
    }
}
