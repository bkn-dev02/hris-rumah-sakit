<?php

namespace Modules\Leave\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Leave\Models\LeaveRequest;
use Modules\Leave\Contracts\Services\LeaveRequestServiceInterface;
use Modules\Master\Models\Department;
use Modules\Master\Models\Employee;

class DashboardLeaveController extends Controller
{
    public function __construct(
        protected LeaveRequestServiceInterface $leaveRequestService,
    ) {}

    public function index(Request $request)
    {
        $roleCodes = $request->user()->roles()->pluck('code')->all();
        $isEmployeeOnly = in_array('pegawai', $roleCodes, true)
            && !array_intersect($roleCodes, ['super-admin', 'admin', 'hrd', 'direktur', 'kepala_unit']);

        if ($isEmployeeOnly) {
            $employee = $request->user()->employee;

            abort_unless($employee, 403, 'Akun Anda tidak terhubung dengan data pegawai.');

            $leaveRequests = $this->leaveRequestService->myRequests($employee);

            return view('leave::employee-index', compact('employee', 'leaveRequests'));
        }

        $isGlobalRole = (bool) array_intersect(
            $roleCodes,
            ['super-admin', 'admin', 'hrd', 'direktur']
        );
        $scopedEmployeeIds = $isGlobalRole ? null : $this->resolveDepartmentEmployeeIds($request);
        $employee = $scopedEmployeeIds !== null || in_array('hrd', $roleCodes, true)
            ? $request->user()->employee
            : null;
        $personalLeaveRequests = $employee
            ? $this->leaveRequestService->myRequests($employee)
            : collect();

        $statuses = ['pending', 'approved', 'rejected', 'cancelled'];
        $activeStatus = $request->string('status')->toString();
        $activeStatus = in_array($activeStatus, $statuses, true) ? $activeStatus : 'all';

        $totalPending = LeaveRequest::query()
            ->when($scopedEmployeeIds !== null, fn($query) => $query->whereIn('employee_id', $scopedEmployeeIds))
            ->where('status', 'pending')
            ->count();
        $totalApprovedThisMonth = LeaveRequest::query()
            ->when($scopedEmployeeIds !== null, fn($query) => $query->whereIn('employee_id', $scopedEmployeeIds))
            ->where('status', 'approved')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();
        $totalRejectedThisMonth = LeaveRequest::query()
            ->when($scopedEmployeeIds !== null, fn($query) => $query->whereIn('employee_id', $scopedEmployeeIds))
            ->where('status', 'rejected')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        $recentRequests = LeaveRequest::query()
            ->with(['employee' => fn($q) => $q->withTrashed(), 'leaveType'])
            ->when($scopedEmployeeIds !== null, fn($query) => $query->whereIn('employee_id', $scopedEmployeeIds))
            ->when($activeStatus !== 'all', fn($query) => $query->where('status', $activeStatus))
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $statusCounts = LeaveRequest::query()
            ->selectRaw('status, count(*) as total')
            ->when($scopedEmployeeIds !== null, fn($query) => $query->whereIn('employee_id', $scopedEmployeeIds))
            ->whereIn('status', $statuses)
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusCounts['all'] = LeaveRequest::query()
            ->when($scopedEmployeeIds !== null, fn($query) => $query->whereIn('employee_id', $scopedEmployeeIds))
            ->count();

        $byType = LeaveRequest::query()
            ->join('leave_types', 'leave_types.id', '=', 'leave_requests.leave_type_id')
            ->selectRaw('leave_types.name, count(*) as total')
            ->when($scopedEmployeeIds !== null, fn($query) => $query->whereIn('leave_requests.employee_id', $scopedEmployeeIds))
            ->whereYear('leave_requests.created_at', now()->year)
            ->groupBy('leave_types.name')
            ->get();

        $topEmployees = LeaveRequest::query()
            ->join('employees', 'employees.id', '=', 'leave_requests.employee_id')
            ->selectRaw('employees.id, employees.name, employees.photo, count(*) as total')
            ->when($scopedEmployeeIds !== null, fn($query) => $query->whereIn('leave_requests.employee_id', $scopedEmployeeIds))
            ->whereYear('leave_requests.created_at', now()->year)
            ->groupBy('employees.id', 'employees.name', 'employees.photo')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $monthlyLabels = [];
        $monthlyCounts = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyLabels[] = \Carbon\Carbon::create(now()->year, $m, 1)->translatedFormat('M');
            $monthlyCounts[] = LeaveRequest::query()
                ->when($scopedEmployeeIds !== null, fn($query) => $query->whereIn('employee_id', $scopedEmployeeIds))
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', $m)
                ->count();
        }

        return view('leave::dashboard', compact(
            'totalPending',
            'totalApprovedThisMonth',
            'totalRejectedThisMonth',
            'recentRequests',
            'activeStatus',
            'statusCounts',
            'byType',
            'topEmployees',
            'monthlyLabels',
            'monthlyCounts',
            'employee',
            'personalLeaveRequests'
        ));
    }

    protected function resolveDepartmentEmployeeIds(Request $request): array
    {
        $department = $request->user()->employee?->currentDepartment();

        if (!$department) {
            return [0];
        }

        $departmentIds = [$department->id];
        $children = Department::whereIn('parent_id', $departmentIds)->pluck('id')->all();

        while ($children) {
            $children = array_values(array_diff($children, $departmentIds));
            if (!$children) {
                break;
            }

            $departmentIds = array_merge($departmentIds, $children);
            $children = Department::whereIn('parent_id', $children)->pluck('id')->all();
        }

        return Employee::withTrashed()
            ->whereHas('placements', fn($query) => $query
                ->active()
                ->whereIn('department_id', array_unique($departmentIds)))
            ->pluck('id')
            ->all();
    }
}
