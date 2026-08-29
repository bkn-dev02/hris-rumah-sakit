<?php

namespace Modules\Attendance\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Attendance\Contracts\Services\AttendanceServiceInterface;
use Modules\Master\Models\Department;
use Modules\Master\Models\Employee;
use Modules\Schedule\Contracts\Services\ScheduleServiceInterface;

class AttendanceDashboardController extends Controller
{
    public function __construct(
        protected AttendanceServiceInterface $attendanceService,
        protected ScheduleServiceInterface $scheduleService,
    ) {}

    public function index(Request $request)
    {
        $summary = $this->attendanceService->todaySummary();
        $recentAttendances = $this->attendanceService->recentTodayForDisplay();

        [$expectedToday, $showFilter, $departmentsForFilter, $departmentId] = $this->buildExpectedTodaySections($request);

        return view('attendance::index', compact(
            'summary',
            'recentAttendances',
            'expectedToday',
            'showFilter',
            'departmentsForFilter',
            'departmentId'
        ));
    }

    protected function buildExpectedTodaySections(Request $request): array
    {
        $actor = $request->user();

        $isGlobalRole = $actor->roles()
            ->whereIn('code', ['super-admin', 'hrd', 'direktur'])
            ->exists();

        $showFilter = true;
        $departmentsForFilter = collect();
        $departmentId = null;

        if ($isGlobalRole) {
            $departmentId = $request->integer('department_id');
            $departmentsForFilter = Department::orderBy('name')->get();
        } else {
            $ownDepartment = $actor->employee?->currentDepartment();

            if ($ownDepartment) {
                $hasChildren = Department::where('parent_id', $ownDepartment->id)->exists();

                if ($hasChildren) {
                    $departmentsForFilter = Department::where('id', $ownDepartment->id)
                        ->orWhere('parent_id', $ownDepartment->id)
                        ->orderBy('name')
                        ->get();
                    $departmentId = $request->integer('department_id') ?: $ownDepartment->id;
                } else {
                    $showFilter = false;
                    $departmentId = $ownDepartment->id;
                    $departmentsForFilter = collect([$ownDepartment]);
                }
            }
        }

        $expectedToday = collect();

        if ($departmentId) {
            $employees = Employee::whereHas('placements', function ($query) use ($departmentId) {
                $query->active()->where('department_id', $departmentId);
            })
                ->where('is_active', true)
                ->orderBy('name')
                ->get();

            $today = Carbon::today();
            $checkInTimes = $this->attendanceService->getCheckInTimesForEmployeesToday($employees->pluck('id')->toArray());

            $byShift = [];

            foreach ($employees as $employee) {
                $resolved = $this->scheduleService->resolveEffectiveShift($employee->id, $today);

                if ($resolved['is_libur'] || !$resolved['shift_id']) {
                    continue;
                }

                $shift = \Modules\Master\Models\Shift::find($resolved['shift_id']);
                if (!$shift) {
                    continue;
                }

                $byShift[$shift->id]['shift'] = $shift;
                $byShift[$shift->id]['employees'][] = [
                    'employee' => $employee,
                    'checked_in_at' => $checkInTimes[$employee->id] ?? null,
                ];
            }

            $expectedToday = collect($byShift)
                ->sortBy(fn($group) => $group['shift']->start_time)
                ->map(function ($group) {
                    $employees = collect($group['employees'])
                        ->sortBy(fn($e) => [$e['checked_in_at'] !== null, $e['employee']->name])
                        ->values();

                    return [
                        'shift' => $group['shift'],
                        'employees' => $employees,
                        'checked_in_count' => $employees->filter(fn($e) => $e['checked_in_at'] !== null)->count(),
                    ];
                })
                ->values();
        }

        return [$expectedToday, $showFilter, $departmentsForFilter, $departmentId];
    }
}
