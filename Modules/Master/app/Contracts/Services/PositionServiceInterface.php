<?php

namespace Modules\Master\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Master\Models\Position;

interface PositionServiceInterface
{
    public function getAll(): Collection;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function findById(int $id): Position;

    public function create(array $data): Position;

    public function update(int $id, array $data): Position;

    public function delete(int $id): bool;
}
