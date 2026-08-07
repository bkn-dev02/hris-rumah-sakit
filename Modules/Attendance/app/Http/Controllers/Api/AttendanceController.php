<?php

namespace Modules\Attendance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Attendance\Contracts\Services\AttendanceServiceInterface;
use Modules\Attendance\Contracts\Services\CheckOutServiceInterface;
use Modules\Attendance\Exceptions\AttendanceException;
use Modules\Attendance\Http\Requests\Api\StoreCheckOutRequest;
use Modules\Shared\Traits\ApiResponse;

class AttendanceController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AttendanceServiceInterface $attendanceService,
        protected CheckOutServiceInterface $checkOutService,
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

    public function checkOut(StoreCheckOutRequest $request)
    {
        $employeeId = $this->resolveEmployeeId($request);

        try {
            $attendance = DB::transaction(function () use ($request, $employeeId) {
                $photoPath = $request->file('photo')->store('attendance/check-outs', 'public');
                $location = \Modules\Attendance\Models\AttendanceLocation::findOrFail($request->location_id);
                $distance = $location->distanceTo((float) $request->latitude, (float) $request->longitude);

                $checkOut = $this->checkOutService->create([
                    'employee_id'     => $employeeId,
                    'checked_at'      => now(),
                    'latitude'        => $request->latitude,
                    'longitude'       => $request->longitude,
                    'photo'           => $photoPath,
                    'location_id'     => $request->location_id,
                    'distance_meters' => (int) $distance,
                    'ip'              => $request->ip(),
                    'device'          => $request->userAgent(),
                    'note'            => $request->note,
                ]);

                return $this->attendanceService->checkOut($employeeId, $checkOut->id);
            });
        } catch (AttendanceException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil.',
            'data'    => $attendance->load(['shift', 'checkIn', 'checkOut', 'status']),
        ], 201);
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
