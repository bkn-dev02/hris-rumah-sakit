<?php

namespace Modules\Attendance\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Attendance\Contracts\Repositories\AttendanceLocationRepositoryInterface;
use Modules\Attendance\Contracts\Services\AttendanceLocationServiceInterface;
use Modules\Attendance\Models\AttendanceLocation;

class AttendanceLocationService implements AttendanceLocationServiceInterface
{
    public function __construct(
        protected AttendanceLocationRepositoryInterface $locationRepository
    ) {}

    public function getAll(): Collection
    {
        return $this->locationRepository->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->locationRepository->paginate($perPage);
    }

    public function activeList(): Collection
    {
        return $this->locationRepository->activeList();
    }

    public function findById(int $id): AttendanceLocation
    {
        $location = $this->locationRepository->findById($id);

        if (!$location) {
            throw new ModelNotFoundException('Lokasi absensi tidak ditemukan.');
        }

        return $location;
    }

    public function create(array $data): AttendanceLocation
    {
        return $this->locationRepository->create($data);
    }

    public function update(int $id, array $data): AttendanceLocation
    {
        $location = $this->findById($id);

        $this->locationRepository->update($location, $data);

        return $location->fresh();
    }

    public function delete(int $id): bool
    {
        $location = $this->findById($id);

        return $this->locationRepository->delete($location);
    }

    public function findMatchingLocation(float $latitude, float $longitude): ?AttendanceLocation
    {
        return $this->locationRepository
            ->activeList()
            ->first(fn(AttendanceLocation $location) => $location->isWithinRadius($latitude, $longitude));
    }
}
