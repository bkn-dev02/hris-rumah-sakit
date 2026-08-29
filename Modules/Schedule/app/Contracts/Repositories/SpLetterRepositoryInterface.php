<?php

namespace Modules\Schedule\Contracts\Repositories;

use Modules\Schedule\Models\SpLetter;

interface SpLetterRepositoryInterface
{
    public function create(array $data): SpLetter;

    public function countForEmployee(int $employeeId): int;

    public function getHistoryForEmployee(int $employeeId);

    public function find(int $id): ?SpLetter;

    public function getForEmployee(int $employeeId);

    public function markViewed(SpLetter $letter): SpLetter;

    public function unreadCountForEmployee(int $employeeId): int;
}
