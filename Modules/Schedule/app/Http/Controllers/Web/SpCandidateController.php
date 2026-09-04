<?php

namespace Modules\Schedule\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Schedule\Contracts\Services\SpCandidateServiceInterface;
use Modules\Schedule\Models\SpCandidate;

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

        if ($isGlobalRole) {
            $allCandidates = $this->spCandidateService->getAll();
        } else {
            $ownDepartment = $actor->employee?->currentDepartment();

            if (!$ownDepartment) {
                abort(403, 'Akun Anda tidak terhubung ke departemen manapun.');
            }

            $allCandidates = $this->spCandidateService->getForDepartment($ownDepartment->id);
        }

        $allCandidates->load(['spLetter', 'employee' => fn($q) => $q->withTrashed()]);

        $grouped = [
            'action' => $allCandidates->whereIn('status', ['candidate', 'pending_decision'])->values(),
            'issued' => $allCandidates->where('status', 'resolved_issued')->sortByDesc('updated_at')->values(),
            'cancelled' => $allCandidates->whereIn('status', ['cancelled_manual', 'cancelled_late_checkin_decision'])->sortByDesc('updated_at')->values(),
        ];

        $tab = $request->input('tab', 'action');
        if (!in_array($tab, ['action', 'issued', 'cancelled'])) {
            $tab = 'action';
        }

        $counts = [
            'action' => $grouped['action']->count(),
            'issued' => $grouped['issued']->count(),
            'cancelled' => $grouped['cancelled']->count(),
        ];

        $personalHistory = collect();
        if ($actor->roles()->where('code', 'kepala_unit')->exists() && $actor->employee) {
            $personalHistory = SpCandidate::query()
                ->where('employee_id', $actor->employee->id)
                ->whereIn('status', ['resolved_issued', 'cancelled_manual', 'cancelled_late_checkin_decision'])
                ->with(['spLetter', 'department'])
                ->latest('updated_at')
                ->get();
        }

        return view('schedule::sp-candidates.index', [
            'candidates' => $grouped[$tab],
            'counts' => $counts,
            'tab' => $tab,
            'personalHistory' => $personalHistory,
        ]);
    }

    public function show(int $spCandidate)
    {
        $candidate = $this->spCandidateService->find($spCandidate);

        if (!$candidate) {
            abort(404);
        }

        $candidate->load(['spLetter', 'employee' => fn($q) => $q->withTrashed()]);

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
