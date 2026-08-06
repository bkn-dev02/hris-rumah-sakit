<?php

namespace Modules\Attendance\Contracts\Services;

use Modules\Attendance\Models\CheckOut;

interface CheckOutServiceInterface
{
    public function create(array $data): CheckOut;
}
