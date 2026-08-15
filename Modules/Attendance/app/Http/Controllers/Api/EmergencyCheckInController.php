<?php

namespace Modules\Attendance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    public function today(Request $request)
    {
        $employee = $request->user()->employee;

        if (! $employee) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak terhubung dengan data pegawai.',
            ], 422);
        }

        $checkIn = $this->checkInService->myEmergencyToday($employee->id);

        if (! $checkIn) {
            return response()->json([
                'success' => true,
                'message' => 'Tidak ada presensi darurat hari ini.',
                'data' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Presensi darurat ditemukan.',
            'data' => [
                'id' => $checkIn->id,
                'checked_at' => $checkIn->checked_at->format('H:i'),
                'emergency_status' => $checkIn->emergency_status,
                'emergency_reason' => $checkIn->emergency_reason,
            ],
        ]);
    }

    public function show(Request $request, int $id)
    {
        $employee = $request->user()->employee;

        if (! $employee) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak terhubung dengan data pegawai.',
            ], 422);
        }

        try {
            $checkIn = $this->checkInService->findMyEmergency($id, $employee->id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail presensi darurat ditemukan.',
            'data' => [
                'id' => $checkIn->id,
                'checked_at' => $checkIn->checked_at->format('d M Y, H:i'),
                'latitude' => (float) $checkIn->latitude,
                'longitude' => (float) $checkIn->longitude,
                'selfie_photo_url' => $checkIn->photo ? asset('storage/' . $checkIn->photo) : null,
                'proof_photo_url' => $checkIn->emergency_photo ? asset('storage/' . $checkIn->emergency_photo) : null,
                'reason' => $checkIn->emergency_reason,
                'status' => $checkIn->emergency_status,
                'decision_note' => $checkIn->emergency_decision_note,
                'decided_at' => $checkIn->emergency_decided_at?->format('d M Y, H:i'),
            ],
        ]);
    }

    public function history(Request $request)
    {
        $employee = $request->user()->employee;

        if (! $employee) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak terhubung dengan data pegawai.',
            ], 422);
        }

        $checkIns = $this->checkInService->myEmergencyHistory($employee->id);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat presensi darurat ditemukan.',
            'data' => $checkIns->map(fn($c) => [
                'id' => $c->id,
                'checked_at' => $c->checked_at->format('d M Y, H:i'),
                'emergency_status' => $c->emergency_status,
                'emergency_reason' => $c->emergency_reason,
            ]),
        ]);
    }
}
