<?php

use Carbon\Carbon;
use Modules\Attendance\Models\Attendance;
use Modules\Attendance\Models\AttendanceStatus;
use Modules\Leave\Models\LeaveRequest;
use Modules\Leave\Models\LeaveRequestApproval;
use Modules\Leave\Models\LeaveType;
use Modules\Master\Models\Department;
use Modules\Master\Models\Employee;
use Modules\Master\Models\EmployeePlacement;
use Modules\Master\Models\EmployeeShiftSchedule;
use Modules\Master\Models\EmploymentStatus;
use Modules\Master\Models\Position;
use Modules\Master\Models\Shift;
use Modules\Security\Models\User;

it('creates attendance rows for approved leave when final approval is granted', function () {
    $user = User::factory()->create();

    $employmentStatus = EmploymentStatus::query()->create([
        'name' => 'Tetap',
        'code' => 'tetap',
        'is_active' => true,
    ]);

    $employee = Employee::query()->create([
        'user_id' => $user->id,
        'slug' => 'pegawai-cuti',
        'employee_number' => 'EMP-001',
        'name' => 'Pegawai Cuti',
        'gender' => 'male',
        'place_of_birth' => 'Bandung',
        'date_of_birth' => '1995-01-01',
        'hire_date' => '2020-01-01',
        'employment_status_id' => $employmentStatus->id,
        'is_active' => true,
    ]);

    $shift = Shift::query()->create([
        'code' => 'SHIFT-1',
        'name' => 'Pagi',
        'start_time' => '08:00:00',
        'end_time' => '17:00:00',
        'effective_date' => '2026-01-01',
        'end_date' => null,
        'is_active' => true,
    ]);

    EmployeeShiftSchedule::query()->create([
        'employee_id' => $employee->id,
        'shift_id' => $shift->id,
        'start_date' => '2026-01-01',
        'end_date' => null,
        'notes' => 'Schedule default',
    ]);

    $leaveType = LeaveType::query()->create([
        'code' => 'CUTI_TAHUNAN',
        'name' => 'Cuti Tahunan',
        'description' => 'Cuti tahunan',
        'is_active' => true,
        'requires_quota' => true,
    ]);

    $leaveRequest = LeaveRequest::query()->create([
        'employee_id' => $employee->id,
        'leave_type_id' => $leaveType->id,
        'start_date' => '2026-08-20',
        'end_date' => '2026-08-21',
        'total_days' => 2,
        'reason' => 'Liburan keluarga',
        'status' => 'pending',
    ]);

    LeaveRequestApproval::query()->create([
        'leave_request_id' => $leaveRequest->id,
        'approver_employee_id' => $employee->id,
        'sequence' => 1,
        'type' => 'director',
        'status' => 'pending',
    ]);

    $service = app(\Modules\Leave\Contracts\Services\LeaveRequestServiceInterface::class);

    $service->decide($leaveRequest->id, $employee, true, null);

    expect($leaveRequest->fresh()->status)->toBe('approved');
    expect(AttendanceStatus::query()->where('code', 'CUTI')->exists())->toBeTrue();
    expect(Attendance::query()->where('employee_id', $employee->id)->whereDate('work_date', '2026-08-20')->exists())->toBeTrue();
    expect(Attendance::query()->where('employee_id', $employee->id)->whereDate('work_date', '2026-08-21')->exists())->toBeTrue();
    expect(Attendance::query()->where('employee_id', $employee->id)->whereDate('work_date', '2026-08-20')->first()->attendance_status_id)->not->toBeNull();
});
