<?php

namespace Modules\Leave\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Leave\Models\LeaveRequest;

class DashboardLeaveController extends Controller
{
    public function index()
    {
        $totalPending = LeaveRequest::query()->where('status', 'pending')->count();
        $totalApprovedThisMonth = LeaveRequest::query()
            ->where('status', 'approved')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();
        $totalRejectedThisMonth = LeaveRequest::query()
            ->where('status', 'rejected')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        $recentRequests = LeaveRequest::query()
            ->with(['employee', 'leaveType'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $byType = LeaveRequest::query()
            ->join('leave_types', 'leave_types.id', '=', 'leave_requests.leave_type_id')
            ->selectRaw('leave_types.name, count(*) as total')
            ->whereYear('leave_requests.created_at', now()->year)
            ->groupBy('leave_types.name')
            ->get();

        $topEmployees = LeaveRequest::query()
            ->join('employees', 'employees.id', '=', 'leave_requests.employee_id')
            ->selectRaw('employees.id, employees.name, employees.photo, count(*) as total')
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
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', $m)
                ->count();
        }

        return view('leave::dashboard', compact(
            'totalPending',
            'totalApprovedThisMonth',
            'totalRejectedThisMonth',
            'recentRequests',
            'byType',
            'topEmployees',
            'monthlyLabels',
            'monthlyCounts'
        ));
    }
}
