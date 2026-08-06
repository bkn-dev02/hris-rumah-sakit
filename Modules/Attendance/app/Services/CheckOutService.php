<?php

namespace Modules\Attendance\Services;

use Modules\Attendance\Contracts\Repositories\CheckOutRepositoryInterface;
use Modules\Attendance\Contracts\Services\CheckOutServiceInterface;
use Modules\Attendance\Models\CheckOut;

class CheckOutService implements CheckOutServiceInterface
{
    public function __construct(protected CheckOutRepositoryInterface $repository) {}

    public function create(array $data): CheckOut
    {
        return $this->repository->create($data);
    }
}
