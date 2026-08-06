<?php

namespace Modules\Attendance\Repositories;

use Modules\Attendance\Contracts\Repositories\CheckOutRepositoryInterface;
use Modules\Attendance\Models\CheckOut;

class CheckOutRepository implements CheckOutRepositoryInterface
{
    protected CheckOut $model;

    public function __construct(CheckOut $model)
    {
        $this->model = $model;
    }

    public function create(array $data): CheckOut
    {
        return $this->model->create($data);
    }

    public function findById(int $id): ?CheckOut
    {
        return $this->model->find($id);
    }
}
