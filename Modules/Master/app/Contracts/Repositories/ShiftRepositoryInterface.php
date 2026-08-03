<?php

namespace Modules\Master\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Master\Models\Shift;

interface ShiftRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function activeList(): Collection;

    public function findById(int $id): ?Shift;

    public function findActiveByCode(string $code): ?Shift;

    public function historyByCode(string $code): Collection;

    public function create(array $data): Shift;

    public function update(Shift $shift, array $data): bool;

    public function delete(Shift $shift): bool;
}
