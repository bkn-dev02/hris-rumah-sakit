<?php

namespace Modules\Security\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Modules\Schedule\Contracts\Services\ScheduleServiceInterface;
use Modules\Master\Models\Shift;

class ProfileController extends Controller
{
    public function __construct(
        protected ScheduleServiceInterface $scheduleService
    ) {}

    public function show(Request $request)
    {
        $user = $request->user()->load('employee.employmentStatus');
        $employee = $user->employee;

        if (! $employee) {
            return response()->json([
                'success' => false,
                'message' => 'Data karyawan tidak ditemukan untuk akun ini.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil ditemukan.',
            'data' => $this->formatEmployee($user, $employee),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user()->load('employee');
        $employee = $user->employee;

        if (! $employee) {
            return response()->json([
                'success' => false,
                'message' => 'Data karyawan tidak ditemukan untuk akun ini.',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'gender' => 'sometimes|in:male,female',
            'place_of_birth' => 'sometimes|nullable|string|max:255',
            'date_of_birth' => 'sometimes|nullable|date',
            'national_id_number' => 'sometimes|nullable|string|max:32',
            'address' => 'sometimes|nullable|string',
            'phone' => 'sometimes|nullable|string|max:20',
            'marital_status' => 'sometimes|nullable|string|max:50',
            'education_level' => 'sometimes|nullable|string|max:50',
            'education_major' => 'sometimes|nullable|string|max:100',
            'photo' => 'sometimes|nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        $employee->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data'    => $this->formatEmployee($user, $employee->fresh()),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui.',
        ]);
    }

    private function formatEmployee($user, $employee): array
    {
        $today = now()->startOfDay();
        $tomorrow = now()->addDay()->startOfDay();

        return [
            'id'                 => $employee->id,
            'username'           => $user->username,
            'email'              => $user->email,
            'employee_number'    => $employee->employee_number,
            'name'               => $employee->name,
            'gender'             => $employee->gender,
            'place_of_birth'     => $employee->place_of_birth,
            'date_of_birth'      => optional($employee->date_of_birth)->format('Y-m-d'),
            'profession'         => $employee->profession,
            'national_id_number' => $employee->national_id_number,
            'address'            => $employee->address,
            'phone'              => $employee->phone,
            'marital_status'     => $employee->marital_status,
            'education_level'    => $employee->education_level,
            'education_major'    => $employee->education_major,
            'photo_url'          => $employee->photo ? Storage::disk('public')->url($employee->photo) : null,
            'hire_date'          => optional($employee->hire_date)->format('Y-m-d'),
            'employment_status'  => $employee->employmentStatus?->name,
            'position'           => $employee->currentPosition()?->name,
            'department'         => $employee->currentDepartment()?->name,
            'schedule_today'     => $this->formatScheduleStatus($employee->id, $today),
            'schedule_tomorrow'  => $this->formatScheduleStatus($employee->id, $tomorrow),
            'is_active'          => (bool) $employee->is_active,
        ];
    }

    private function formatScheduleStatus(int $employeeId, \Carbon\Carbon $date): array
    {
        $resolved = $this->scheduleService->resolveDisplayStatus($employeeId, $date);

        if ($resolved['is_libur']) {
            return [
                'is_libur' => true,
                'shift_name' => null,
                'shift_time' => null,
            ];
        }

        if ($resolved['is_undetermined']) {
            return [
                'is_libur' => false,
                'shift_name' => null,
                'shift_time' => null,
            ];
        }

        $shift = $resolved['shift_id'] ? Shift::find($resolved['shift_id']) : null;

        return [
            'is_libur' => false,
            'shift_name' => $shift?->name,
            'shift_time' => $this->formatShiftTime($shift),
        ];
    }

    private function formatShiftTime($shift): ?string
    {
        if (! $shift || ! $shift->start_time || ! $shift->end_time) {
            return null;
        }

        $start = \Carbon\Carbon::parse($shift->start_time)->format('H:i');
        $end = \Carbon\Carbon::parse($shift->end_time)->format('H:i');

        return "{$start} - {$end}";
    }
}
