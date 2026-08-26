<?php

namespace Modules\Schedule\Http\Controllers\Web;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Models\Employee;
use Modules\Master\Models\Shift;
use Modules\Master\Models\Department;
use Modules\Schedule\Contracts\Services\ScheduleServiceInterface;

class ScheduleController extends Controller
{
    public function __construct(
        protected ScheduleServiceInterface $scheduleService
    ) {}

    public function index(Request $request)
    {
        $departmentId = $request->integer('department_id');
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))
            : Carbon::now()->startOfWeek();
        $endDate = $startDate->copy()->addDays(6);

        $departments = Department::orderBy('name')->get();
        $shifts = Shift::orderBy('start_time')->get();

        $employees = collect();
        $scheduleMap = [];

        if ($departmentId) {
            $employees = Employee::whereHas('placements', function ($query) use ($departmentId) {
                $query->active()->where('department_id', $departmentId);
            })
                ->where('is_active', true)
                ->orderBy('name')
                ->get();

            $scheduleMap = $this->scheduleService->getScheduleMapForGrid(
                $employees->pluck('id')->toArray(),
                $startDate,
                $endDate
            );
        }

        $dates = collect(range(0, 6))->map(fn($i) => $startDate->copy()->addDays($i));

        return view('schedule::schedules.index', compact(
            'employees',
            'dates',
            'shifts',
            'departments',
            'departmentId',
            'startDate',
            'endDate',
            'scheduleMap'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'date' => ['required', 'date'],
            'type' => ['required', 'in:kerja,libur'],
            'shift_id' => ['nullable', 'required_if:type,kerja', 'exists:shifts,id'],
        ]);

        $schedule = $this->scheduleService->assign(
            employeeId: $validated['employee_id'],
            date: Carbon::parse($validated['date']),
            type: $validated['type'],
            shiftId: $validated['shift_id'] ?? null,
            createdByEmployeeId: $request->user()->employee->id,
        );

        return response()->json([
            'message' => 'Jadwal berhasil disimpan.',
            'data' => $schedule,
        ]);
    }
}
