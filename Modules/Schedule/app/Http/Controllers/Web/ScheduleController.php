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

            if (!$ownDepartment) {
                abort(403, 'Akun Anda tidak terhubung ke departemen manapun.');
            }

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
            }
        }

        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))
            : Carbon::now()->startOfWeek();
        $endDate = $startDate->copy()->addDays(6);

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
            'departmentsForFilter',
            'showFilter',
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

        $actor = $request->user();
        $createdByEmployeeId = $actor->employee?->id;

        // kalau user bukan pegawai, hanya yang memuliki role super admin yang boleh lanjut
        $isSuperAdmin = $actor->roles()
            ->where('code', 'super-admin')
            ->where('is_active', true)
            ->exists();

        if (!$createdByEmployeeId && !$isSuperAdmin) {
            abort(403, 'Akun Anda bukan Pegawai.');
        }

        $schedule = $this->scheduleService->assign(
            employeeId: $validated['employee_id'],
            date: Carbon::parse($validated['date']),
            type: $validated['type'],
            shiftId: $validated['shift_id'] ?? null,
            createdByEmployeeId: $createdByEmployeeId,
            actorUserId: $actor->id,
        );

        return response()->json([
            'message' => 'Jadwal berhasil disimpan.',
            'data' => $schedule,
        ]);
    }
}
