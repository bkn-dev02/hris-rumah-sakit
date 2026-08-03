<?php

namespace Modules\Master\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Modules\Master\Contracts\Repositories\ShiftRepositoryInterface;
use Modules\Master\Contracts\Services\ShiftServiceInterface;
use Modules\Master\DTOs\ShiftVersionData;
use Modules\Master\Models\Shift;

class ShiftService implements ShiftServiceInterface
{
    public function __construct(
        protected ShiftRepositoryInterface $shiftRepository
    ) {}

    public function getAll(): Collection
    {
        return $this->shiftRepository->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->shiftRepository->paginate($perPage);
    }

    public function activeList(): Collection
    {
        return $this->shiftRepository->activeList();
    }

    public function findById(int $id): Shift
    {
        $shift = $this->shiftRepository->findById($id);

        if (!$shift) {
            throw new ModelNotFoundException('Shift tidak ditemukan.');
        }

        return $shift;
    }

    public function historyByCode(string $code): Collection
    {
        return $this->shiftRepository->historyByCode($code);
    }

    public function createNewVersion(ShiftVersionData $data): Shift
    {
        return DB::transaction(function () use ($data) {

            $currentVersion = $this->shiftRepository->findActiveByCode($data->code);

            if ($currentVersion) {
                $this->shiftRepository->update($currentVersion, [
                    'end_date' => $data->effectiveDate->copy()->subDay(),
                ]);
            }

            return $this->shiftRepository->create([
                'code'           => $data->code,
                'name'           => $data->name,
                'start_time'     => $data->startTime,
                'end_time'       => $data->endTime,
                'effective_date' => $data->effectiveDate,
                'end_date'       => null,
                'is_active'      => true,
            ]);
        });
    }

    public function delete(int $id): bool
    {
        $shift = $this->findById($id);

        return $this->shiftRepository->delete($shift);
    }
}
