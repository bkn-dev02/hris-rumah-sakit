<?php

namespace Modules\Schedule\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Schedule\Contracts\Services\SpCandidateServiceInterface;

class SpCandidateController extends Controller
{
    public function __construct(
        protected SpCandidateServiceInterface $spCandidateService
    ) {}

    public function index(Request $request)
    {
        $actor = $request->user();

        $isGlobalRole = $actor->roles()
            ->whereIn('code', ['super-admin', 'hrd', 'direktur'])
            ->exists();

        $status = $request->input('status');

        if ($isGlobalRole) {
            $candidates = $this->spCandidateService->getAll($status);
        } else {
            $ownDepartment = $actor->employee?->currentDepartment();

            if (!$ownDepartment) {
                abort(403, 'Akun Anda tidak terhubung ke departemen manapun.');
            }

            $candidates = $this->spCandidateService->getForDepartment($ownDepartment->id, $status);
        }

        return view('schedule::sp-candidates.index', compact('candidates', 'status'));
    }

    public function show(int $spCandidate)
    {
        $candidate = $this->spCandidateService->find($spCandidate);

        if (!$candidate) {
            abort(404);
        }

        $candidate->load('spLetter');

        return view('schedule::sp-candidates.show', compact('candidate'));
    }

    public function confirm(Request $request, int $spCandidate)
    {
        $validated = $request->validate([
            'note' => ['required', 'string', 'max:1000'],
        ]);

        $candidate = $this->spCandidateService->find($spCandidate);
        if (!$candidate) {
            abort(404);
        }

        $this->spCandidateService->recordManualConfirmation(
            employeeId: $candidate->employee_id,
            date: $candidate->date,
            shiftId: $candidate->shift_id,
            note: $validated['note'],
            confirmedByEmployeeId: $request->user()->employee->id,
            spCandidateId: $candidate->id,
        );

        return back()->with('success', 'Konfirmasi manual berhasil disimpan.');
    }

    public function decide(Request $request, int $spCandidate)
    {
        $validated = $request->validate([
            'issue_sp' => ['required', 'boolean'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->spCandidateService->decide(
            spCandidateId: $spCandidate,
            issueSp: $validated['issue_sp'],
            decidedByEmployeeId: $request->user()->employee->id,
            note: $validated['note'] ?? null,
        );

        return back()->with('success', 'Keputusan berhasil disimpan.');
    }
}
