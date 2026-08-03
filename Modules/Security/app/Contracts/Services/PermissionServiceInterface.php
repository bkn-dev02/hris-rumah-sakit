<?php

namespace Modules\Security\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Security\Models\Permission;

interface PermissionServiceInterface
{
    public function getAll(): Collection;

    public function paginate(int $perPage = 15, ?string $module = null): LengthAwarePaginator;

    public function modules(): Collection;

    public function findById(int $id): Permission;

    public function create(array $data): Permission;

    public function update(int $id, array $data): Permission;

    public function delete(int $id): bool;
}
