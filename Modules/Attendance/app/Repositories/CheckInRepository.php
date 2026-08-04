<?php

namespace Modules\Attendance\Repositories;

use Modules\Attendance\Contracts\Repositories\CheckInRepositoryInterface;
use Modules\Attendance\Models\CheckIn;

class CheckInRepository implements CheckInRepositoryInterface
{
    protected CheckIn $model;

    public function __construct(CheckIn $model)
    {
        $this->model = $model;
    }

    public function findById(int $id): ?CheckIn
    {
        return $this->model->with(['employee', 'location'])->find($id);
    }

    public function create(array $data): CheckIn
    {
        return $this->model->create($data);
    }

    public function update(CheckIn $checkIn, array $data): bool
    {
        return $checkIn->update($data);
    }

    public function delete(CheckIn $checkIn): bool
    {
        return (bool) $checkIn->delete();
    }

    public function findByEmployeeAndDate(int $employeeId, string $date): ?CheckIn
    {
        return $this->model
            ->where('employee_id', $employeeId)
            ->whereDate('checked_at', $date)
            ->first();
    }
}
