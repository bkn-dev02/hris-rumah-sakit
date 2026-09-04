<?php

namespace Modules\Schedule\Http\Controllers\Web;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Models\Department;
use Modules\Master\Models\Employee;
use Modules\Schedule\Contracts\Services\ScheduleServiceInterface;

class MonthlyGridController extends Controller
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
                $departmentsForFilter = collect([$ownDepartment]);
            }
        }

        $year = $request->integer('year') ?: now()->year;
        $month = $request->integer('month') ?: now()->month;
        $monthDate = Carbon::createFromDate($year, $month, 1);
        $startDate = $monthDate->copy()->startOfMonth();
        $endDate = $monthDate->copy()->endOfMonth();

        $employees = collect();
        $scheduleMap = [];
        $personalSchedule = [];

        if ($actor->roles()->where('code', 'kepala_unit')->exists() && $actor->employee) {
            $personalSchedule = $this->scheduleService->getEmployeeSchedule(
                $actor->employee->id,
                $startDate,
                $endDate
            );
        }

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

        $dates = collect();
        for ($d = $startDate->copy(); $d->lte($endDate); $d->addDay()) {
            $dates->push($d->copy());
        }

        return view('schedule::monthly-grid.index', compact(
            'employees',
            'dates',
            'departmentsForFilter',
            'showFilter',
            'departmentId',
            'monthDate',
            'scheduleMap',
            'personalSchedule'
        ));
    }

    public function personal(Request $request)
    {
        $employee = $request->user()->employee;

        if (!$employee) {
            abort(403, 'Akun Anda tidak terhubung dengan data pegawai.');
        }

        $year = $request->integer('year') ?: now()->year;
        $month = $request->integer('month') ?: now()->month;
        $monthDate = Carbon::createFromDate($year, $month, 1);
        $startDate = $monthDate->copy()->startOfMonth();
        $endDate = $monthDate->copy()->endOfMonth();
        $schedule = $this->scheduleService->getEmployeeSchedule($employee->id, $startDate, $endDate);

        return view('schedule::monthly-grid.personal', compact('employee', 'monthDate', 'schedule'));
    }
}
