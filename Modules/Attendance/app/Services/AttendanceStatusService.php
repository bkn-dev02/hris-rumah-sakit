<?php

namespace Modules\Attendance\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Attendance\Contracts\Repositories\AttendanceStatusRepositoryInterface;
use Modules\Attendance\Contracts\Services\AttendanceStatusServiceInterface;
use Modules\Attendance\Models\AttendanceStatus;

class AttendanceStatusService implements AttendanceStatusServiceInterface
{
    public function __construct(
        protected AttendanceStatusRepositoryInterface $statusRepository
    ) {}

    public function getAll(): Collection
    {
        return $this->statusRepository->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->statusRepository->paginate($perPage);
    }

    public function activeList(): Collection
    {
        return $this->statusRepository->activeList();
    }

    public function findById(int $id): AttendanceStatus
    {
        $status = $this->statusRepository->findById($id);

        if (!$status) {
            throw new ModelNotFoundException('Status kehadiran tidak ditemukan.');
        }

        return $status;
    }

    public function create(array $data): AttendanceStatus
    {
        return $this->statusRepository->create($data);
    }

    public function update(int $id, array $data): AttendanceStatus
    {
        $status = $this->findById($id);

        $this->statusRepository->update($status, $data);

        return $status->fresh();
    }

    public function delete(int $id): bool
    {
        $status = $this->findById($id);

        return $this->statusRepository->delete($status);
    }
}
