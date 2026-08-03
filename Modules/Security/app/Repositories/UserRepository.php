<?php

namespace Modules\Security\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Security\Contracts\Repositories\UserRepositoryInterface;
use Modules\Security\Models\User;

class UserRepository implements UserRepositoryInterface
{
    protected User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->orderBy('username')->get();
    }

    public function paginate(int $perPage = 25, bool $trashed = false): LengthAwarePaginator
    {
        return $this->model
            ->when($trashed, fn($query) => $query->onlyTrashed())
            ->orderBy('username')
            ->paginate($perPage);
    }

    public function findBySlug(string $slug, bool $withTrashed = false): ?User
    {
        return $this->model
            ->when($withTrashed, fn($query) => $query->withTrashed())
            ->where('slug', $slug)
            ->first();
    }

    public function findByUsername(string $username): ?User
    {
        return $this->model
            ->where('username', $username)
            ->first();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model
            ->where('email', $email)
            ->first();
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function delete(User $user): bool
    {
        return (bool) $user->delete();
    }

    public function restore(string $slug): bool
    {
        $user = $this->model
            ->onlyTrashed()
            ->where('slug', $slug)
            ->first();

        if (! $user) {
            return false;
        }

        return (bool) $user->restore();
    }

    public function forceDelete(User $user): bool
    {
        return (bool) $user->forceDelete();
    }

    public function availableForEmployee(?int $includeUserId = null): Collection
    {
        return $this->model
            ->whereDoesntHave('employee')
            ->when($includeUserId, fn($query) => $query->orWhere('id', $includeUserId))
            ->orderBy('username')
            ->get();
    }
}
