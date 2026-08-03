<?php

namespace Modules\Attendance\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Modules\Attendance\Contracts\Services\AttendanceServiceInterface;

class AttendanceDashboardController extends Controller
{
    public function __construct(
        protected AttendanceServiceInterface $attendanceService
    ) {}

    public function index()
    {
        $summary = $this->attendanceService->todaySummary();
        $recentAttendances = $this->attendanceService->recentToday(10);

        return view('attendance::index', compact('summary', 'recentAttendances'));
    }
}
