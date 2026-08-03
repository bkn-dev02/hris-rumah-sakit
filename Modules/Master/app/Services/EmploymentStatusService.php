<?php

namespace Modules\Master\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Master\Contracts\Repositories\EmploymentStatusRepositoryInterface;
use Modules\Master\Contracts\Services\EmploymentStatusServiceInterface;
use Modules\Master\Models\EmploymentStatus;

class EmploymentStatusService implements EmploymentStatusServiceInterface
{
    public function __construct(
        protected EmploymentStatusRepositoryInterface $employmentStatusRepository
    ) {}

    public function getAll(): Collection
    {
        return $this->employmentStatusRepository->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->employmentStatusRepository->paginate($perPage);
    }

    public function findById(int $id): EmploymentStatus
    {
        $employmentStatus = $this->employmentStatusRepository->findById($id);

        if (!$employmentStatus) {
            throw new ModelNotFoundException('Employment status tidak ditemukan.');
        }

        return $employmentStatus;
    }

    public function create(array $data): EmploymentStatus
    {
        return $this->employmentStatusRepository->create($data);
    }

    public function update(int $id, array $data): EmploymentStatus
    {
        $employmentStatus = $this->findById($id);

        $this->employmentStatusRepository->update($employmentStatus, $data);

        return $employmentStatus->fresh();
    }

    public function delete(int $id): bool
    {
        $employmentStatus = $this->findById($id);

        return $this->employmentStatusRepository->delete($employmentStatus);
    }
}
