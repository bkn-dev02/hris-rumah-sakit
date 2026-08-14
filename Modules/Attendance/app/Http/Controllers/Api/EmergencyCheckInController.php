<?php

namespace Modules\Attendance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Attendance\Contracts\Services\CheckInServiceInterface;
use Modules\Attendance\Http\Requests\Api\StoreEmergencyCheckInRequest;
use Modules\Shared\Traits\ApiResponse;

class EmergencyCheckInController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected CheckInServiceInterface $checkInService,
    ) {}

    public function store(StoreEmergencyCheckInRequest $request)
    {
        $employee = $request->user()->employee;

        if (! $employee) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak terhubung dengan data pegawai.',
            ], 422);
        }

        $selfiePath = $request->file('selfie_photo')->store('attendance/emergency/selfie', 'public');
        $proofPath = $request->file('proof_photo')->store('attendance/emergency/proof', 'public');

        $checkIn = $this->checkInService->createEmergency([
            'employee_id' => $employee->id,
            'checked_at' => now(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'photo' => $selfiePath,
            'emergency_photo' => $proofPath,
            'emergency_reason' => $request->reason,
            'ip' => $request->ip(),
            'device' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Presensi darurat berhasil dikirim, menunggu persetujuan HRD.',
            'data' => $checkIn,
        ], 201);
    }
}
