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

    public function getForEmployee(int $employeeId)
    {
        return SpLetter::where('employee_id', $employeeId)
            ->orderByDesc('issued_at')
            ->get();
    }

    public function markViewed(SpLetter $letter): SpLetter
    {
        if (!$letter->viewed_at) {
            $letter->update(['viewed_at' => now()]);
        }

        return $letter->fresh();
    }

    public function unreadCountForEmployee(int $employeeId): int
    {
        return SpLetter::where('employee_id', $employeeId)
            ->whereNull('viewed_at')
            ->count();
    }
}
