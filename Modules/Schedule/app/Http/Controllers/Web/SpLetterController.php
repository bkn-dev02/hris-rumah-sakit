<?php

namespace Modules\Schedule\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Schedule\Contracts\Services\SpLetterServiceInterface;

class SpLetterController extends Controller
{
    public function __construct(
        protected SpLetterServiceInterface $spLetterService
    ) {}

    public function store(Request $request, int $spCandidate)
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $this->spLetterService->issue(
            spCandidateId: $spCandidate,
            file: $validated['file'],
            issuedByEmployeeId: $request->user()->employee->id,
        );

        return redirect()
            ->route('schedule.sp-candidates.index')
            ->with('success', 'Surat SP berhasil diterbitkan.');
    }
}
