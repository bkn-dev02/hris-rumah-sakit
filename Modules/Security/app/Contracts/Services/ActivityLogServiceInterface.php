<?php

namespace Modules\Security\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ActivityLogServiceInterface
{
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function historyFor(Model $subject): Collection;
}
