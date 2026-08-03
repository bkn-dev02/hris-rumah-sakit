<?php

namespace Modules\Security\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Security\Contracts\Repositories\PermissionRepositoryInterface;
use Modules\Security\Contracts\Services\PermissionServiceInterface;
use Modules\Security\Models\Permission;

class PermissionService implements PermissionServiceInterface
{
    public function __construct(
        protected PermissionRepositoryInterface $permissionRepository
    ) {}

    public function getAll(): Collection
    {
        return $this->permissionRepository->all();
    }

    public function paginate(int $perPage = 15, ?string $module = null): LengthAwarePaginator
    {
        return $this->permissionRepository->paginate($perPage, $module);
    }

    public function modules(): \Illuminate\Support\Collection
    {
        return $this->permissionRepository->modules();
    }

    public function findById(int $id): Permission
    {
        $permission = $this->permissionRepository->findById($id);

        if (!$permission) {
            throw new ModelNotFoundException('Permission tidak ditemukan.');
        }

        return $permission;
    }

    public function create(array $data): Permission
    {
        return $this->permissionRepository->create($data);
    }

    public function update(int $id, array $data): Permission
    {
        $permission = $this->findById($id);

        $this->permissionRepository->update($permission, $data);

        return $permission->fresh();
    }

    public function delete(int $id): bool
    {
        $permission = $this->findById($id);

        return $this->permissionRepository->delete($permission);
    }
}
