<?php

namespace Modules\Attendance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Modules\Attendance\Contracts\Services\CheckInServiceInterface;
use Modules\Attendance\Http\Requests\StoreCheckInRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Attendance\Contracts\Services\AttendanceServiceInterface;
use Modules\Attendance\Exceptions\AttendanceException;

class CheckInController extends Controller
{
    public function __construct(
        protected CheckInServiceInterface $checkInService,
        protected AttendanceServiceInterface $attendanceService,
    ) {}

    public function store(StoreCheckInRequest $request)
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan.'
            ], 404);
        }

        $alreadyCheckIn = $this->checkInService
            ->findByEmployeeAndDate($employee->id, now()->toDateString());

        if ($alreadyCheckIn) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah check-in hari ini.'
            ], 422);
        }

        try {
            $checkIn = DB::transaction(function () use ($request, $employee) {
                $photoPath = $request->file('photo')->store('attendance/check-ins', 'public');

                $location = \Modules\Attendance\Models\AttendanceLocation::findOrFail($request->location_id);
                $distance = $location->distanceTo((float) $request->latitude, (float) $request->longitude);

                $checkIn = $this->checkInService->create([
                    'employee_id' => $employee->id,
                    'checked_at' => now(),
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'photo' => $photoPath,
                    'location_id' => $request->location_id,
                    'distance_meters' => (int) $distance,
                    'ip' => $request->ip(),
                    'device' => $request->userAgent(),
                    'note' => $request->note,
                ]);

                $this->attendanceService->checkIn($employee->id, $checkIn->id);

                return $checkIn;
            });
        } catch (AttendanceException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil.',
            'data' => $checkIn
        ], 201);
    }

    public function destroy(int $check_in)
    {
        try {
            $this->checkInService->delete($check_in);
        } catch (QueryException $e) {
            return back()->with('error', 'Check-in tidak dapat dihapus.');
        }

        return redirect()
            ->route('attendance.check-ins.index')
            ->with('success', 'Check-in berhasil dihapus.');
    }
}
