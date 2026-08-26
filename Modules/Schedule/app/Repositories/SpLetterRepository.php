<?php

namespace Modules\Schedule\Repositories;

use Modules\Schedule\Contracts\Repositories\SpLetterRepositoryInterface;
use Modules\Schedule\Models\SpLetter;

class SpLetterRepository implements SpLetterRepositoryInterface
{
    public function create(array $data): SpLetter
    {
        return SpLetter::create($data);
    }

    public function countForEmployee(int $employeeId): int
    {
        return SpLetter::where('employee_id', $employeeId)->count();
    }

    public function getHistoryForEmployee(int $employeeId)
    {
        return SpLetter::where('employee_id', $employeeId)
            ->orderBy('sp_number')
            ->get();
    }

    public function find(int $id): ?SpLetter
    {
        return SpLetter::find($id);
    }
}
