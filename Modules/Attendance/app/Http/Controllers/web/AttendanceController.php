<?php

namespace Modules\Attendance\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Attendance\Contracts\Services\AttendanceCorrectionServiceInterface;
use Modules\Attendance\Contracts\Services\AttendanceServiceInterface;
use Modules\Attendance\Contracts\Services\AttendanceStatusServiceInterface;
use Modules\Attendance\Exceptions\AttendanceException;
use Modules\Attendance\Http\Requests\CorrectAttendanceStatusRequest;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceServiceInterface $attendanceService,
        protected AttendanceCorrectionServiceInterface $correctionService,
        protected AttendanceStatusServiceInterface $statusService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['employee_id', 'status_id', 'start_date', 'end_date']);

        $attendances = $this->attendanceService->paginate(15, $filters);
        $statuses = $this->statusService->activeList();

        return view('attendance::attendances.index', compact('attendances', 'statuses'));
    }

    public function show(int $attendance)
    {
        $attendance = $this->attendanceService->findById($attendance);
        $corrections = $this->correctionService->history($attendance->id);
        $statuses = $this->statusService->activeList();

        return view('attendance::attendances.show', compact('attendance', 'corrections', 'statuses'));
    }

    public function correctStatus(CorrectAttendanceStatusRequest $request, int $attendance)
    {
        try {
            $this->attendanceService->correctStatus(
                $attendance,
                $request->validated('attendance_status_id'),
                $request->validated('reason'),
                Auth::id()
            );
        } catch (AttendanceException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('attendance.attendances.show', $attendance)
            ->with('success', 'Status kehadiran berhasil dikoreksi.');
    }
}
