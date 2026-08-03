<?php

namespace Modules\Master\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Master\DTOs\ShiftVersionData;
use Modules\Master\Models\Shift;

interface ShiftServiceInterface
{
    public function getAll(): Collection;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function activeList(): Collection;

    public function findById(int $id): Shift;

    public function historyByCode(string $code): Collection;

    public function createNewVersion(ShiftVersionData $data): Shift;

    public function delete(int $id): bool;
}
