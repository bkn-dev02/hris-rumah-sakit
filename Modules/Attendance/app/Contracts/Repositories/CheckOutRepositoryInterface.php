<?php

namespace Modules\Attendance\Contracts\Repositories;

use Modules\Attendance\Models\CheckOut;

interface CheckOutRepositoryInterface
{
    public function create(array $data): CheckOut;
    public function findById(int $id): ?CheckOut;
}
