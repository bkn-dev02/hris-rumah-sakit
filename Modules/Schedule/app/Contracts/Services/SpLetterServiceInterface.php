<?php

namespace Modules\Schedule\Contracts\Services;

use Illuminate\Http\UploadedFile;
use Modules\Schedule\Models\SpLetter;

interface SpLetterServiceInterface
{
    /**
     * Issue an official SP letter for a resolved SpCandidate.
     * Computes sp_number as count of existing SpLetters for the employee + 1.
     */
    public function issue(int $spCandidateId, UploadedFile $file, int $issuedByEmployeeId): SpLetter;

    /**
     * Get SP history/count for an employee (for the dashboard "SP ke berapa" display).
     */
    public function getHistoryForEmployee(int $employeeId): array;
}
