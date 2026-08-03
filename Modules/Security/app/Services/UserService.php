<?php

namespace Modules\Security\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Modules\Security\Contracts\Repositories\UserRepositoryInterface;
use Modules\Security\Models\User;
use Illuminate\Support\Str;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll(): Collection
    {
        return $this->userRepository->all();
    }

    public function paginate(int $perPage = 25, bool $trashed = false): LengthAwarePaginator
    {
        return $this->userRepository->paginate($perPage, $trashed);
    }

    public function findBySlug(string $slug, bool $withTrashed = false): User
    {
        $user = $this->userRepository->findBySlug($slug, $withTrashed);

        if (!$user) {
            throw new ModelNotFoundException('User tidak ditemukan.');
        }

        return $user;
    }

    public function findByUsername(string $username): ?User
    {
        return $this->userRepository->findByUsername($username);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    protected function generateUniqueSlug(string $username): string
    {
        $slug = Str::slug($username);
        $original = $slug;
        $count = 1;

        while ($this->userRepository->findBySlug($slug, withTrashed: true)) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $roleIds = $data['roles'] ?? [];
            unset($data['roles']);

            $data['slug'] = $this->generateUniqueSlug($data['username']);

            $user = $this->userRepository->create($data);

            $user->roles()->sync($roleIds);

            return $user->fresh('roles');
        });
    }

    public function update(string $slug, array $data): User
    {
        return DB::transaction(function () use ($slug, $data) {

            $user = $this->findBySlug($slug);

            $roleIds = $data['roles'] ?? [];
            unset($data['roles']);

            if (empty($data['password'])) {
                unset($data['password']);
            }

            $this->userRepository->update($user, $data);

            $user->roles()->sync($roleIds);

            return $user->fresh('roles');
        });
    }

    public function delete(string $slug): bool
    {
        return DB::transaction(function () use ($slug) {

            $user = $this->findBySlug($slug);

            return $this->userRepository->delete($user);
        });
    }

    public function restore(string $slug): bool
    {
        return DB::transaction(function () use ($slug) {

            return $this->userRepository->restore($slug);
        });
    }

    public function forceDelete(string $slug): bool
    {
        return DB::transaction(function () use ($slug) {

            $user = $this->findBySlug($slug, withTrashed: true);

            return $this->userRepository->forceDelete($user);
        });
    }

    public function availableForEmployee(?int $includeUserId = null): Collection
    {
        return $this->userRepository->availableForEmployee($includeUserId);
    }

    public function updatePassword(User $user, string $newPassword): void
    {
        $this->userRepository->update($user, ['password' => $newPassword]);
    }
}
