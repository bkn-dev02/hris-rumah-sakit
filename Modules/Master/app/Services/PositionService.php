<?php

namespace Modules\Master\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Master\Contracts\Repositories\PositionRepositoryInterface;
use Modules\Master\Contracts\Services\PositionServiceInterface;
use Modules\Master\Models\Position;

class PositionService implements PositionServiceInterface
{
    public function __construct(
        protected PositionRepositoryInterface $positionRepository
    ) {}

    public function getAll(): Collection
    {
        return $this->positionRepository->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->positionRepository->paginate($perPage);
    }

    public function findById(int $id): Position
    {
        $position = $this->positionRepository->findById($id);

        if (!$position) {
            throw new ModelNotFoundException('Position tidak ditemukan.');
        }

        return $position;
    }

    public function create(array $data): Position
    {
        return $this->positionRepository->create($data);
    }

    public function update(int $id, array $data): Position
    {
        $position = $this->findById($id);

        $this->positionRepository->update($position, $data);

        return $position->fresh();
    }

    public function delete(int $id): bool
    {
        $position = $this->findById($id);

        return $this->positionRepository->delete($position);
    }
}
