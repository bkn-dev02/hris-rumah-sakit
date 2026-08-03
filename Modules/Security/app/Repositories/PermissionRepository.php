<?php

namespace Modules\Security\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Modules\Security\Contracts\Repositories\PermissionRepositoryInterface;
use Modules\Security\Models\Permission;

class PermissionRepository implements PermissionRepositoryInterface
{
    protected Permission $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    public function all(): EloquentCollection
    {
        return $this->model->orderBy('module')->orderBy('code')->get();
    }

    public function paginate(int $perPage = 15, ?string $module = null): LengthAwarePaginator
    {
        return $this->model
            ->when($module, fn($query, $value) => $query->where('module', $value))
            ->orderBy('module')
            ->orderBy('code')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $id): ?Permission
    {
        return $this->model->find($id);
    }

    public function modules(): Collection
    {
        return $this->model->select('module')->distinct()->orderBy('module')->pluck('module');
    }

    public function create(array $data): Permission
    {
        return $this->model->create($data);
    }

    public function update(Permission $permission, array $data): bool
    {
        return $permission->update($data);
    }

    public function delete(Permission $permission): bool
    {
        return (bool) $permission->delete();
    }
}
