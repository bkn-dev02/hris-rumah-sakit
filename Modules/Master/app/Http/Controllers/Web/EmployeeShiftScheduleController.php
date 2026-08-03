<?php

namespace Modules\Master\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Master\Http\Requests\StoreEmployeeShiftScheduleRequest;
use Modules\Master\Contracts\Services\EmployeeServiceInterface;
use Modules\Master\Contracts\Services\EmployeeShiftScheduleServiceInterface;
use Modules\Master\Contracts\Services\ShiftServiceInterface;
use Modules\Master\DTOs\EmployeeShiftScheduleData;

class EmployeeShiftScheduleController extends Controller
{
    public function __construct(
        protected EmployeeShiftScheduleServiceInterface $scheduleService,
        protected EmployeeServiceInterface $employeeService,
        protected ShiftServiceInterface $shiftService
    ) {}

    public function index(string $employee)
    {
        $employee = $this->employeeService->findBySlug($employee);
        $history = $this->scheduleService->history($employee->id);

        return view('master::employees.shift-schedules.index', compact('employee', 'history'));
    }

    public function create(string $employee)
    {
        $employee = $this->employeeService->findBySlug($employee);
        $currentSchedule = $this->scheduleService->current($employee->id);
        $shifts = $this->shiftService->activeList();

        return view('master::employees.shift-schedules.create', compact(
            'employee',
            'currentSchedule',
            'shifts'
        ));
    }

    public function store(StoreEmployeeShiftScheduleRequest $request, string $employee)
    {
        $employee = $this->employeeService->findBySlug($employee);

        $data = EmployeeShiftScheduleData::fromArray([
            ...$request->validated(),
            'employee_id' => $employee->id,
            'created_by'  => Auth::id(),
        ]);

        $this->scheduleService->createSchedule($data);

        return redirect()
            ->route('master.employees.shift-schedules.index', $employee->slug)
            ->with('success', 'Jadwal shift berhasil disimpan.');
    }
}
