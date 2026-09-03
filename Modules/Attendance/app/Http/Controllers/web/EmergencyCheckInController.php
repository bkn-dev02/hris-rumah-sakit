<?php

namespace Modules\Attendance\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Attendance\Contracts\Services\CheckInServiceInterface;
use Modules\Attendance\Http\Requests\StoreEmergencyCheckInRequest;

class EmergencyCheckInController extends Controller
{
    public function __construct(
        protected CheckInServiceInterface $checkInService,
    ) {}

    public function create(Request $request)
    {
        $employee = $request->user()->employee;

        if (! $employee) {
            abort(403, 'Akun Anda tidak terhubung dengan data pegawai.');
        }

        $existing = $this->checkInService->myEmergencyToday($employee->id);

        return view('attendance::emergency.create', compact('existing'));
    }

    public function store(StoreEmergencyCheckInRequest $request)
    {
        $employee = $request->user()->employee;

        if (! $employee) {
            return back()->with('error', 'Akun Anda tidak terhubung dengan data pegawai.');
        }

        if ($this->checkInService->myEmergencyToday($employee->id)) {
            return back()->with('error', 'Anda sudah mengajukan presensi darurat hari ini.');
        }

        $selfiePath = $request->file('selfie_photo')->store('attendance/emergency/selfie', 'public');
        $proofPath  = $request->file('proof_photo')->store('attendance/emergency/proof', 'public');

        $this->checkInService->createEmergency([
            'employee_id'      => $employee->id,
            'checked_at'       => now(),
            'latitude'         => $request->input('latitude'),
            'longitude'        => $request->input('longitude'),
            'photo'            => $selfiePath,
            'emergency_photo'  => $proofPath,
            'emergency_reason' => $request->validated('reason'),
            'ip'               => $request->ip(),
            'device'           => $request->userAgent(),
        ]);

        return redirect()
            ->route('attendance.emergency-request.create')
            ->with('success', 'Presensi darurat berhasil dikirim, menunggu persetujuan HRD.');
    }
}
