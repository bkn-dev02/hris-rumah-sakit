<?php

namespace Modules\Schedule\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Schedule\Contracts\Repositories\SpCandidateRepositoryInterface;
use Modules\Schedule\Contracts\Repositories\SpLetterRepositoryInterface;
use Modules\Schedule\Contracts\Services\SpLetterServiceInterface;
use Modules\Schedule\Models\SpLetter;

class SpLetterService implements SpLetterServiceInterface
{
    public function __construct(
        protected SpLetterRepositoryInterface $spLetterRepository,
        protected SpCandidateRepositoryInterface $spCandidateRepository
    ) {}

    public function issue(int $spCandidateId, UploadedFile $file, int $issuedByEmployeeId): SpLetter
    {
        $spCandidate = $this->spCandidateRepository->find($spCandidateId);

        $path = Storage::disk('public')->put('sp-letters', $file);

        $spNumber = $this->spLetterRepository->countForEmployee($spCandidate->employee_id) + 1;

        $letter = $this->spLetterRepository->create([
            'sp_candidate_id' => $spCandidate->id,
            'employee_id' => $spCandidate->employee_id,
            'file_path' => $path,
            'sp_number' => $spNumber,
            'issued_by' => $issuedByEmployeeId,
            'issued_at' => now(),
        ]);

        $this->spCandidateRepository->update($spCandidate, [
            'status' => 'resolved_issued',
        ]);

        // TODO: notify the employee that an SP letter has been issued

        return $letter;
    }

    public function getHistoryForEmployee(int $employeeId): array
    {
        return $this->spLetterRepository->getHistoryForEmployee($employeeId)->toArray();
    }

    public function getMyLetters(int $employeeId): array
    {
        return $this->spLetterRepository->getForEmployee($employeeId)->toArray();
    }

    public function getMyLetterDetail(int $employeeId, int $letterId): SpLetter
    {
        $letter = $this->spLetterRepository->find($letterId);

        if (!$letter || $letter->employee_id !== $employeeId) {
            throw new ModelNotFoundException('Surat SP tidak ditemukan.');
        }

        return $this->spLetterRepository->markViewed($letter);
    }

    public function unreadCount(int $employeeId): int
    {
        return $this->spLetterRepository->unreadCountForEmployee($employeeId);
    }
}
