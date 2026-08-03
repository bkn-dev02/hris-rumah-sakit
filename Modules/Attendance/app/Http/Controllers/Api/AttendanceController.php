<?php

namespace Modules\Attendance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Attendance\Contracts\Services\AttendanceServiceInterface;
use Modules\Attendance\DTOs\CheckInData;
use Modules\Attendance\DTOs\CheckOutData;
use Modules\Attendance\Exceptions\AttendanceException;
use Modules\Attendance\Http\Requests\Api\CheckInRequest;
use Modules\Attendance\Http\Requests\Api\CheckOutRequest;
use Modules\Shared\Traits\ApiResponse;

class AttendanceController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AttendanceServiceInterface $attendanceService
    ) {}

    public function checkIn(CheckInRequest $request)
    {
        try {
            $employeeId = $this->resolveEmployeeId($request);

            $photoPath = $request->file('photo')->store('attendance/check-ins', 'public');

            $attendance = $this->attendanceService->checkIn(CheckInData::fromArray([
                'employee_id' => $employeeId,
                'latitude'    => $request->validated('latitude'),
                'longitude'   => $request->validated('longitude'),
                'photo_path'  => $photoPath,
            ]));

            return $this->success($attendance, 'Check-in berhasil.');
        } catch (AttendanceException $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    public function checkOut(CheckOutRequest $request)
    {
        try {
            $employeeId = $this->resolveEmployeeId($request);

            $photoPath = $request->file('photo')->store('attendance/check-outs', 'public');

            $attendance = $this->attendanceService->checkOut(CheckOutData::fromArray([
                'employee_id' => $employeeId,
                'latitude'    => $request->validated('latitude'),
                'longitude'   => $request->validated('longitude'),
                'photo_path'  => $photoPath,
            ]));

            return $this->success($attendance, 'Check-out berhasil.');
        } catch (AttendanceException $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

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
                'name'          => $location->name,
                'latitude'      => (float) $location->latitude,
                'longitude'     => (float) $location->longitude,
                'radius_meters' => $location->radius_meters,
            ],
        ]);
    }

    public function today(Request $request)
    {
        $employeeId = $this->resolveEmployeeId($request);

        $attendance = $this->attendanceService->todayFor($employeeId);

        return $this->success($attendance);
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
