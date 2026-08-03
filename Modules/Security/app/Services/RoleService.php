<?php

namespace Modules\Security\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Modules\Security\Contracts\Repositories\RoleRepositoryInterface;
use Modules\Security\Models\Role;

class RoleService
{
    protected RoleRepositoryInterface $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getAll(): Collection
    {
        return $this->roleRepository->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->roleRepository->paginate($perPage);
    }

    public function findById(int $id): Role
    {
        $role = $this->roleRepository->findById($id);

        if (!$role) {
            throw new ModelNotFoundException('Role tidak ditemukan.');
        }

        return $role;
    }

    public function create(array $data): Role
    {
        return DB::transaction(function () use ($data) {

            $permissionIds = $data['permissions'] ?? [];
            unset($data['permissions']);

            $role = $this->roleRepository->create($data);

            $this->roleRepository->syncPermissions($role, $permissionIds);

            return $role->fresh('permissions');
        });
    }

    public function update(int $id, array $data): Role
    {
        return DB::transaction(function () use ($id, $data) {

            $role = $this->findById($id);

            $this->guardAgainstSystemRole($role, 'diubah');

            $permissionIds = $data['permissions'] ?? [];
            unset($data['permissions']);

            $this->roleRepository->update($role, $data);
            $this->roleRepository->syncPermissions($role, $permissionIds);

            return $role->fresh('permissions');
        });
    }

    public function delete(int $id): bool
    {
        $role = $this->findById($id);

        $this->guardAgainstSystemRole($role, 'dihapus');

        return $this->roleRepository->delete($role);
    }

    protected function guardAgainstSystemRole(Role $role, string $action): void
    {
        if ($role->is_system) {
            throw new \RuntimeException("Role sistem tidak dapat {$action}.");
        }
    }
}
