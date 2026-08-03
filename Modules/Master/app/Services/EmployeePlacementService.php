<?php

namespace Modules\Master\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Master\Contracts\Repositories\EmployeePlacementRepositoryInterface;
use Modules\Master\Contracts\Repositories\PositionRepositoryInterface;
use Modules\Master\Contracts\Services\EmployeePlacementServiceInterface;
use Modules\Master\DTOs\EmployeePlacementData;
use Modules\Master\Models\EmployeePlacement;

class EmployeePlacementService implements EmployeePlacementServiceInterface
{
    public function __construct(
        protected EmployeePlacementRepositoryInterface $placementRepository,
        protected PositionRepositoryInterface $positionRepository
    ) {}

    public function history(int $employeeId): Collection
    {
        return $this->placementRepository->historyByEmployee($employeeId);
    }

    public function current(int $employeeId): ?EmployeePlacement
    {
        return $this->placementRepository->findActiveByEmployee($employeeId);
    }

    public function createPlacement(EmployeePlacementData $data): EmployeePlacement
    {
        return DB::transaction(function () use ($data) {

            $currentPlacement = $this->placementRepository->findActiveByEmployee($data->employeeId);

            $placementType = $this->determinePlacementType($currentPlacement, $data);

            if ($currentPlacement) {
                $this->placementRepository->update($currentPlacement, [
                    'end_date' => $data->startDate->copy()->subDay(),
                ]);
            }

            return $this->placementRepository->create([
                'employee_id' => $data->employeeId,
                'department_id' => $data->departmentId,
                'position_id' => $data->positionId,
                'origin_placement_id' => $data->isTemporary ? $currentPlacement?->id : null,
                'placement_type' => $placementType,
                'start_date' => $data->startDate,
                'end_date' => null,
                'notes' => $data->notes,
                'created_by' => $data->createdBy,
            ]);
        });
    }

    protected function determinePlacementType(?EmployeePlacement $current, EmployeePlacementData $data): string
    {
        if (!$current) {
            return 'initial';
        }

        if ($data->isTemporary) {
            return 'temporary';
        }

        if ($current->position_id === $data->positionId) {
            return 'mutation';
        }

        $newPosition = $this->positionRepository->findById($data->positionId);
        $currentLevel = $current->position->level;

        return match (true) {
            $newPosition->level > $currentLevel => 'promotion',
            $newPosition->level < $currentLevel => 'demotion',
            default => 'mutation',
        };
    }
}
