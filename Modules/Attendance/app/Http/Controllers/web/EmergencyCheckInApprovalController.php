<?php

namespace Modules\Attendance\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RuntimeException;
use Modules\Attendance\Contracts\Services\CheckInServiceInterface;

class EmergencyCheckInApprovalController extends Controller
{
    public function __construct(
        protected CheckInServiceInterface $checkInService,
    ) {}

    public function index()
    {
        $checkIns = $this->checkInService->pendingEmergencies();
        $this->checkInService->markEmergencySeen();
        return view('attendance::emergency.index', compact('checkIns'));
    }

    public function decide(Request $request, int $id)
    {
        $request->validate([
            'decision' => ['required', 'in:approve,reject'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $employee = $request->user()->employee;

        if (! $employee) {
            abort(422, 'Akun Anda tidak terhubung dengan data pegawai.');
        }

        try {
            $this->checkInService->decideEmergency(
                $id,
                $employee,
                $request->decision === 'approve',
                $request->note
            );
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('attendance.emergency.index')
            ->with('success', 'Presensi darurat berhasil diproses.');
    }
}
