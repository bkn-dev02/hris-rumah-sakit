<?php

namespace Modules\Attendance\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RuntimeException;
use Modules\Attendance\Contracts\Services\CheckInServiceInterface;
use Modules\Attendance\Contracts\Services\AttendanceServiceInterface;
use Modules\Attendance\Exceptions\AttendanceException;

class EmergencyCheckInApprovalController extends Controller
{
    public function __construct(
        protected CheckInServiceInterface $checkInService,
        protected AttendanceServiceInterface $attendanceService,
    ) {}

    public function index(Request $request)
    {
        $actor = $request->user();

        $isGlobalRole = $actor->roles()
            ->whereIn('code', ['super-admin', 'admin', 'hrd', 'direktur'])
            ->exists();

        $showFilter = true;
        $departmentsForFilter = collect();
        $departmentId = null;

        if ($isGlobalRole) {
            $departmentId = $request->integer('department_id');
            $departmentsForFilter = \Modules\Master\Models\Department::orderBy('name')->get();
        } else {
            $ownDepartment = $actor->employee?->currentDepartment();

            if (!$ownDepartment) {
                abort(403, 'Akun Anda tidak terhubung ke departemen manapun.');
            }

            $hasChildren = \Modules\Master\Models\Department::where('parent_id', $ownDepartment->id)->exists();

            if ($hasChildren) {
                $departmentsForFilter = \Modules\Master\Models\Department::where('id', $ownDepartment->id)
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

        $tab = $request->input('tab', 'pending');
        if (!in_array($tab, ['pending', 'rejected', 'approved'])) {
            $tab = 'pending';
        }

        $checkIns = $this->checkInService->getEmergencyHistory($departmentId, $tab);

        if ($tab === 'pending') {
            $this->checkInService->markEmergencySeen();
        }

        return view('attendance::emergency.index', compact(
            'checkIns',
            'showFilter',
            'departmentsForFilter',
            'departmentId',
            'tab'
        ));
    }

    public function decide(Request $request, int $id)
    {
        $request->validate([
            'decision' => ['required', 'in:approve,reject'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $employee = $request->user()->employee;

        if (! $employee) {
            abort(422, 'Akun Anda tidak terhubung dengan data pegawai.');
        }

        try {
            $checkIn = $this->checkInService->decideEmergency(
                $id,
                $employee,
                $request->decision === 'approve',
                $request->note
            );

            if ($request->decision === 'approve') {
                $this->attendanceService->checkIn($checkIn->employee_id, $checkIn->id);
            }
        } catch (RuntimeException | AttendanceException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('attendance.emergency.index')
            ->with('success', 'Presensi darurat berhasil diproses.');
    }

    public function history(Request $request)
    {
        $actor = $request->user();

        $isGlobalRole = $actor->roles()
            ->whereIn('code', ['super-admin', 'admin', 'hrd', 'direktur'])
            ->exists();

        $showFilter = true;
        $departmentsForFilter = collect();
        $departmentId = null;

        if ($isGlobalRole) {
            $departmentId = $request->integer('department_id');
            $departmentsForFilter = \Modules\Master\Models\Department::orderBy('name')->get();
        } else {
            $ownDepartment = $actor->employee?->currentDepartment();

            if (!$ownDepartment) {
                abort(403, 'Akun Anda tidak terhubung ke departemen manapun.');
            }

            $hasChildren = \Modules\Master\Models\Department::where('parent_id', $ownDepartment->id)->exists();

            if ($hasChildren) {
                $departmentsForFilter = \Modules\Master\Models\Department::where('id', $ownDepartment->id)
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

        $status = $request->input('status');
        $checkIns = $this->checkInService->getEmergencyHistory($departmentId, $status);

        return view('attendance::emergency.history', compact(
            'checkIns',
            'showFilter',
            'departmentsForFilter',
            'departmentId',
            'status'
        ));
    }

    public function show(int $id)
    {
        $checkIn = $this->checkInService->findById($id);

        if ($checkIn->type !== 'emergency') {
            abort(404);
        }

        return view('attendance::emergency.show', compact('checkIn'));
    }
}
