<?php

namespace Modules\Master\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Attendance\Models\AttendanceLocation;
use Modules\Master\Http\Requests\StoreEmployeeRequest;
use Modules\Master\Http\Requests\UpdateEmployeeRequest;
use Modules\Master\Contracts\Services\EmployeeServiceInterface;
use Modules\Master\Contracts\Services\EmploymentStatusServiceInterface;
use Modules\Master\DTOs\EmployeeData;
use Modules\Security\Services\UserService;
use Modules\Master\Models\Employee;
use Modules\Leave\Contracts\Repositories\LeaveTypeRepositoryInterface;
use Modules\Leave\Contracts\Repositories\EmployeeLeaveQuotaRepositoryInterface;
use Modules\Leave\Contracts\Repositories\LeaveRequestRepositoryInterface;

class EmployeeController extends Controller
{
    public function __construct(
        protected EmployeeServiceInterface $employeeService,
        protected EmploymentStatusServiceInterface $employmentStatusService,
        protected UserService $userService,
        protected LeaveTypeRepositoryInterface $leaveTypeRepository,
        protected EmployeeLeaveQuotaRepositoryInterface $employeeLeaveQuotaRepository,
        protected LeaveRequestRepositoryInterface $leaveRequestRepository
    ) {}

    public function index(Request $request)
    {
        $employees = $this->employeeService->paginate(10, trashed: $request->boolean('trashed'));
        $attendanceLocations = AttendanceLocation::query()
            ->active()
            ->orderBy('name')
            ->get();

        return view('master::employees.index', compact('employees', 'attendanceLocations'));
    }

    public function create()
    {
        $employee = new Employee();
        $employmentStatuses = $this->employmentStatusService->getAll();
        $users = $this->userService->availableForEmployee();

        return view('master::employees.create', compact('employmentStatuses', 'users', 'employee'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        $validated = $request->validated();

        $photo = $request->hasFile('photo')
            ? $request->file('photo')->store('employees/photos', 'public')
            : null;

        $this->employeeService->create(EmployeeData::fromArray($validated), $photo);

        return redirect()
            ->route('master.employees.index')
            ->with('success', 'Employee berhasil ditambahkan.');
    }

    public function show(string $employee)
    {
        $employee = $this->employeeService->findBySlug($employee);
        $quotaYear = now()->year;
        $leaveQuotas = $this->employeeLeaveQuotaRepository
            ->forEmployeeYear($employee->id, $quotaYear)
            ->map(function ($quota) use ($employee, $quotaYear) {
                $usedDays = $this->leaveRequestRepository->usedDaysByEmployeeAndType(
                    $employee->id,
                    $quota->leave_type_id,
                    $quotaYear,
                );

                return [
                    'leave_type' => $quota->leaveType,
                    'quota_days' => $quota->quota_days,
                    'used_days' => $usedDays,
                    'remaining_days' => max(0, $quota->quota_days - $usedDays),
                ];
            });
        $attendances = $employee->attendances()
            ->with(['shift', 'status', 'checkIn', 'checkOut'])
            ->latest('work_date')
            ->get();

        return view('master::employees.show', compact('employee', 'attendances', 'leaveQuotas', 'quotaYear'));
    }

    public function edit(Request $request, string $employee)
    {
        $employee = $this->employeeService->findBySlug($employee);
        $employmentStatuses = $this->employmentStatusService->getAll();
        $users = $this->userService->availableForEmployee($employee->user_id);

        $quotaYear = (int) $request->get('quota_year', now()->year);
        $leaveTypes = $this->leaveTypeRepository->allActiveRequiringQuota();
        $existingQuotas = $this->employeeLeaveQuotaRepository->forEmployeeYear($employee->id, $quotaYear);

        $usedDays = $leaveTypes->mapWithKeys(function ($leaveType) use ($employee, $quotaYear) {
            return [$leaveType->id => $this->leaveRequestRepository->usedDaysByEmployeeAndType($employee->id, $leaveType->id, $quotaYear)];
        });

        return view('master::employees.edit', compact(
            'employee',
            'employmentStatuses',
            'users',
            'leaveTypes',
            'existingQuotas',
            'quotaYear',
            'usedDays'
        ));
    }

    public function update(UpdateEmployeeRequest $request, string $employee)
    {
        $validated = $request->validated();

        $photo = null;

        if ($request->hasFile('photo')) {
            $current = $this->employeeService->findBySlug($employee);

            if ($current->photo) {
                Storage::disk('public')->delete($current->photo);
            }

            $photo = $request->file('photo')->store('employees/photos', 'public');
        }

        $this->employeeService->update($employee, EmployeeData::fromArray($validated), $photo);

        return redirect()
            ->route('master.employees.index')
            ->with('success', 'Employee berhasil diperbarui.');
    }

    public function destroy(string $employee)
    {
        $this->employeeService->delete($employee);

        return redirect()
            ->route('master.employees.index')
            ->with('success', 'Employee berhasil dihapus.');
    }

    public function setAttendanceLocation(Request $request, string $employee)
    {
        $validated = $request->validate([
            'attendance_location_id' => ['nullable', 'integer', 'exists:attendance_locations,id'],
        ]);

        $employeeModel = $this->employeeService->findBySlug($employee);
        $employeeModel->update([
            'attendance_location_id' => $validated['attendance_location_id'] ?? null,
        ]);

        return redirect()
            ->route('master.employees.index')
            ->with('success', 'Lokasi absensi pegawai berhasil diperbarui.');
    }

    public function restore(string $employee)
    {
        $this->employeeService->restore($employee);

        return redirect()
            ->route('master.employees.index')
            ->with('success', 'Employee berhasil dipulihkan.');
    }

    public function forceDelete(string $employee)
    {
        $this->employeeService->forceDelete($employee);

        return redirect()
            ->route('master.employees.index')
            ->with('success', 'Employee berhasil dihapus permanen.');
    }
}
