<?php

namespace Modules\Master\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Master\Contracts\Repositories\ShiftRepositoryInterface;
use Modules\Master\Models\Shift;

class ShiftRepository implements ShiftRepositoryInterface
{
    protected Shift $model;

    public function __construct(Shift $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->orderBy('code')->orderByDesc('effective_date')->get();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->whereNull('end_date')
            ->orderBy('code')
            ->paginate($perPage);
    }

    public function activeList(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->whereNull('end_date')
            ->orderBy('code')
            ->get();
    }

    public function findById(int $id): ?Shift
    {
        return $this->model->find($id);
    }

    public function findActiveByCode(string $code): ?Shift
    {
        return $this->model
            ->where('code', $code)
            ->whereNull('end_date')
            ->first();
    }

    public function historyByCode(string $code): Collection
    {
        return $this->model
            ->where('code', $code)
            ->orderByDesc('effective_date')
            ->get();
    }

    public function create(array $data): Shift
    {
        return $this->model->create($data);
    }

    public function update(Shift $shift, array $data): bool
    {
        return $shift->update($data);
    }

    public function delete(Shift $shift): bool
    {
        return (bool) $shift->delete();
    }
}
