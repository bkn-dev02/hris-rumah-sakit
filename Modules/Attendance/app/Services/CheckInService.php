<?php

namespace Modules\Attendance\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Attendance\Contracts\Repositories\CheckInRepositoryInterface;
use Modules\Attendance\Contracts\Services\CheckInServiceInterface;
use Modules\Attendance\Models\CheckIn;


class CheckInService implements CheckInServiceInterface
{
    public function __construct(protected CheckInRepositoryInterface $repository) {}

    public function findById(int $id): CheckIn
    {
        $checkIn = $this->repository->findById($id);

        if (! $checkIn) {
            throw new ModelNotFoundException('Check-in tidak ditemukan.');
        }

        return $checkIn;
    }

    public function create(array $data): CheckIn
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): CheckIn
    {
        $checkIn = $this->findById($id);

        $this->repository->update($checkIn, $data);

        return $checkIn->fresh();
    }

    public function delete(int $id): bool
    {
        $checkIn = $this->findById($id);

        return $this->repository->delete($checkIn);
    }

    public function findByEmployeeAndDate(int $employeeId, string $workDate): ?CheckIn
    {
        return $this->repository->findByEmployeeAndDate($employeeId, $workDate);
    }
}
