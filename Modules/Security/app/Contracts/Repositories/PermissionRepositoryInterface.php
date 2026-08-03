<?php

namespace Modules\Security\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Modules\Security\Models\Permission;

interface PermissionRepositoryInterface
{
    public function all(): EloquentCollection;

    public function paginate(int $perPage = 15, ?string $module = null): LengthAwarePaginator;

    public function findById(int $id): ?Permission;

    public function modules(): Collection;

    public function create(array $data): Permission;

    public function update(Permission $permission, array $data): bool;

    public function delete(Permission $permission): bool;
}
