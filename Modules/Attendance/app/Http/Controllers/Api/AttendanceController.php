<?php

namespace Modules\Attendance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Attendance\Contracts\Services\AttendanceServiceInterface;
use Modules\Attendance\Exceptions\AttendanceException;
use Modules\Shared\Traits\ApiResponse;

class AttendanceController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AttendanceServiceInterface $attendanceService
    ) {}

    public function myLocation(Request $request)
    {
        $employee = $request->user()->employee;
        $location = $employee?->attendanceLocation;

        if (! $location) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi presensi belum ditentukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lokasi ditemukan.',
            'data' => [
                'id' => $location->id,
                'name' => $location->name,
                'latitude' => (float) $location->latitude,
                'longitude' => (float) $location->longitude,
                'radius_meters' => $location->radius_meters,
            ],
        ]);
    }

    public function today(Request $request)
    {
        $employeeId = $this->resolveEmployeeId($request);

        $data = $this->attendanceService->todayForDisplay($employeeId);

        return response()->json([
            'success' => true,
            'message' => $data ? 'Data ditemukan.' : 'Belum ada absensi hari ini.',
            'data'    => $data,
        ]);
    }

    public function history(Request $request)
    {
        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $employeeId = $this->resolveEmployeeId($request);

        $data = $this->attendanceService->historyForDisplay(
            $employeeId,
            $request->input('start_date'),
            $request->input('end_date'),
        );

        return response()->json([
            'success' => true,
            'message' => 'Riwayat ditemukan.',
            'data'    => $data,
        ]);
    }

    protected function resolveEmployeeId(Request $request): int
    {
        $employee = $request->user()->employee;

        if (!$employee) {
            throw new AttendanceException('Akun Anda tidak terhubung dengan data pegawai.');
        }

        return $employee->id;
    }
}
