<?php

namespace Modules\Security\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Security\Models\User;

interface UserRepositoryInterface
{
    public function all(): Collection;

    public function paginate(
        int $perPage = 10,
        bool $trashed = false
    ): LengthAwarePaginator;

    public function findBySlug(
        string $slug,
        bool $withTrashed = false
    ): ?User;

    public function findByUsername(
        string $username
    ): ?User;

    public function findByEmail(
        string $email
    ): ?User;

    public function create(
        array $data
    ): User;

    public function update(
        User $user,
        array $data
    ): bool;

    public function delete(
        User $user
    ): bool;

    public function restore(
        string $slug
    ): bool;

    public function forceDelete(
        User $user
    ): bool;

    public function availableForEmployee(?int $includeUserId = null): Collection;
}
