<?php

namespace Modules\Security\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Security\Contracts\Repositories\RoleRepositoryInterface;
use Modules\Security\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    protected Role $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model
            ->withCount('permissions')
            ->orderBy('name')
            ->get();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->withCount('permissions')
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Role
    {
        return $this->model
            ->with('permissions')
            ->find($id);
    }

    public function create(array $data): Role
    {
        return $this->model->create($data);
    }

    public function update(Role $role, array $data): bool
    {
        return $role->update($data);
    }

    public function delete(Role $role): bool
    {
        return (bool) $role->delete();
    }

    public function syncPermissions(Role $role, array $permissionIds): void
    {
        $role->permissions()->sync($permissionIds);
    }
}
