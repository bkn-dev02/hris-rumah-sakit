<?php

namespace Modules\Master\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Master\Models\Position;

interface PositionRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function findById(int $id): ?Position;

    public function create(array $data): Position;

    public function update(Position $position, array $data): bool;

    public function delete(Position $position): bool;
}
