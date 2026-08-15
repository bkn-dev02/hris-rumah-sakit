<?php

namespace Modules\Attendance\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RuntimeException;
use Modules\Attendance\Contracts\Services\CheckInServiceInterface;
use Modules\Attendance\Contracts\Services\AttendanceServiceInterface;
use Modules\Attendance\Exceptions\AttendanceException;

class EmergencyCheckInApprovalController extends Controller
{
    public function __construct(
        protected CheckInServiceInterface $checkInService,
        protected AttendanceServiceInterface $attendanceService,
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
            $checkIn = $this->checkInService->decideEmergency(
                $id,
                $employee,
                $request->decision === 'approve',
                $request->note
            );

            if ($request->decision === 'approve') {
                $this->attendanceService->checkIn($checkIn->employee_id, $checkIn->id);
            }
        } catch (RuntimeException | AttendanceException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('attendance.emergency.index')
            ->with('success', 'Presensi darurat berhasil diproses.');
    }
}
