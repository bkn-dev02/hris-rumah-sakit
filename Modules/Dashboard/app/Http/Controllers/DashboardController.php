<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Attendance\Contracts\Services\AttendanceServiceInterface;
use Modules\Attendance\Models\Attendance;
use Modules\Master\Contracts\Services\EmployeeServiceInterface;
use Modules\Master\Models\Employee;
use Modules\Leave\Models\LeaveRequest;
use Modules\Attendance\Models\CheckIn;

class DashboardController extends Controller
{
    public function __construct(
        protected AttendanceServiceInterface $attendanceService,
        protected EmployeeServiceInterface $employeeService,
    ) {}

    public function index(Request $request)
    {
        $actor = $request->user();

        $isElevated = $actor->roles()
            ->whereIn('code', ['super-admin', 'admin', 'hrd', 'direktur', 'kepala-unit'])
            ->exists();

        if (!$isElevated && $actor->employee) {
            return $this->personalDashboard($actor->employee);
        }

        $summary = $this->attendanceService->todaySummary();

        $stats = [
            'total' => (int) ($summary['total'] ?? 0),
            'aktif' => (int) ($summary['aktif'] ?? 0),
            'nonaktif' => (int) ($summary['nonaktif'] ?? 0),
            'present' => (int) ($summary['present'] ?? 0),
            'late' => (int) ($this->lateTodayCount() ?? 0),
            'absent' => (int) ($summary['absent'] ?? 0),
            'leave' => (int) ($summary['on_leave'] ?? 0),
        ];

        $stats['present_pct'] = $stats['total'] > 0 ? round(($stats['present'] / $stats['total']) * 100) : 0;
        $stats['late_pct'] = $stats['present'] > 0 ? round(($stats['late'] / $stats['present']) * 100) : 0;
        $stats['absent_pct'] = $stats['total'] > 0 ? round(($stats['absent'] / $stats['total']) * 100) : 0;

        $chartData = $this->buildChartData();

        $departmentDistribution = $this->employeeService->getDepartmentDistribution();

        $recentActivities = $this->buildRecentActivities();

        $quickAccessMenus = $this->buildQuickAccessMenus();

        $pendingLeaveCount = \Modules\Leave\Models\LeaveRequest::query()
            ->where('status', 'pending')
            ->count();

        $pendingEmergencyCount = \Modules\Attendance\Models\CheckIn::query()
            ->where('type', 'emergency')
            ->where('emergency_status', 'pending')
            ->count();

        $pendingSpCandidateCount = \Modules\Schedule\Models\SpCandidate::query()
            ->whereIn('status', ['candidate', 'pending_decision'])
            ->count();

        return view('dashboard::index', compact(
            'stats',
            'chartData',
            'departmentDistribution',
            'recentActivities',
            'quickAccessMenus',
            'pendingLeaveCount',
            'pendingEmergencyCount',
            'pendingSpCandidateCount',
        ));
    }

    protected function personalDashboard(Employee $employee)
    {
        $today = $this->attendanceService->todayForDisplay($employee->id);
        $monthSummary = $this->attendanceService->getMonthlyPersonalSummary($employee->id, now()->year, now()->month);

        return view('dashboard::personal', [
            'employee' => $employee,
            'today' => $today,
            'monthSummary' => $monthSummary,
            'monthLabel' => now()->translatedFormat('F Y'),
        ]);
    }

    protected function buildChartData(): array
    {
        $base = [
            ['day' => 'Sen', 'height' => '70%', 'value' => '980'],
            ['day' => 'Sel', 'height' => '82%', 'value' => '1,045'],
            ['day' => 'Rab', 'height' => '76%', 'value' => '1,012'],
            ['day' => 'Kam', 'height' => '90%', 'value' => '1,085'],
            ['day' => 'Jum', 'height' => '84%', 'value' => '1,060'],
            ['day' => 'Sab', 'height' => '45%', 'value' => '580'],
            ['day' => 'Min', 'height' => '30%', 'value' => '390'],
        ];

        $days = [];
        $maxCount = 0;
        $today = Carbon::today();

        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $count = Attendance::query()->whereDate('work_date', $date)->count();
            $days[] = [
                'day' => $date->translatedFormat('D'),
                'value' => (int) $count,
            ];
            $maxCount = max($maxCount, $count);
        }

        $hasRealData = collect($days)->contains(fn($item) => (int) $item['value'] > 0);

        if (! $hasRealData) {
            return $base;
        }

        $realChart = [];
        foreach ($days as $item) {
            $value = (int) $item['value'];
            $height = $maxCount > 0 ? max(18, round(($value / $maxCount) * 100)) : 18;

            $realChart[] = [
                'day' => $item['day'],
                'height' => $height . '%',
                'value' => number_format($value),
            ];
        }

        return $realChart;
    }

    protected function lateTodayCount(): ?int
    {
        $late = Attendance::query()
            ->whereDate('work_date', today())
            ->whereNotNull('attendance_status_id')
            ->whereHas('status', fn($query) => $query->where('code', 'TERLAMBAT'))
            ->count();

        return $late > 0 ? $late : null;
    }

    protected function buildRecentActivities(int $limit = 10): array
    {
        $activities = collect();

        Employee::query()
            ->latest('created_at')
            ->limit(5)
            ->get()
            ->each(function ($employee) use ($activities) {
                $activities->push([
                    'icon' => 'fa-user-plus',
                    'color' => 'bg-sky-100 text-sky-950',
                    'title' => 'Pegawai baru ditambahkan',
                    'description' => "{$employee->name} bergabung sebagai {$employee->currentPosition()?->name}",
                    'timestamp' => $employee->created_at,
                ]);
            });

        LeaveRequest::query()
            ->with(['employee' => fn($q) => $q->withTrashed()])
            ->whereIn('status', ['approved', 'rejected'])
            ->latest('updated_at')
            ->limit(5)
            ->get()
            ->each(function ($leave) use ($activities) {
                $isApproved = $leave->status === 'approved';
                $activities->push([
                    'icon' => $isApproved ? 'fa-calendar-check' : 'fa-calendar-xmark',
                    'color' => $isApproved ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700',
                    'title' => $isApproved ? 'Pengajuan cuti disetujui' : 'Pengajuan cuti ditolak',
                    'description' => "Pengajuan cuti oleh {$leave->employee->name}",
                    'timestamp' => $leave->updated_at,
                ]);
            });

        CheckIn::query()
            ->with(['employee' => fn($q) => $q->withTrashed()])
            ->where('type', 'emergency')
            ->latest('updated_at')
            ->limit(5)
            ->get()
            ->each(function ($checkIn) use ($activities) {
                $isPending = $checkIn->emergency_status === 'pending';
                $isApproved = $checkIn->emergency_status === 'approved';

                $activities->push([
                    'icon' => 'fa-triangle-exclamation',
                    'color' => $isPending
                        ? 'bg-amber-100 text-amber-700'
                        : ($isApproved ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'),
                    'title' => $isPending
                        ? 'Presensi darurat diajukan'
                        : ($isApproved ? 'Presensi darurat disetujui' : 'Presensi darurat ditolak'),
                    'description' => "Presensi darurat oleh {$checkIn->employee->name}",
                    'timestamp' => $checkIn->updated_at,
                ]);
            });

        \Modules\Schedule\Models\SpCandidate::query()
            ->with(['employee' => fn($q) => $q->withTrashed()])
            ->latest('updated_at')
            ->limit(5)
            ->get()
            ->each(function ($candidate) use ($activities) {
                $isIssued = $candidate->status === 'resolved_issued';
                $isCancelled = in_array($candidate->status, ['cancelled_manual', 'cancelled_late_checkin_decision']);

                $activities->push([
                    'icon' => $isIssued ? 'fa-file-signature' : ($isCancelled ? 'fa-circle-check' : 'fa-triangle-exclamation'),
                    'color' => $isIssued
                        ? 'bg-rose-100 text-rose-700'
                        : ($isCancelled ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'),
                    'title' => $isIssued
                        ? 'Surat SP diterbitkan'
                        : ($isCancelled ? 'SP Candidate dibatalkan' : 'SP Candidate baru terdeteksi'),
                    'description' => "SP Candidate untuk {$candidate->employee->name}",
                    'timestamp' => $candidate->updated_at,
                ]);
            });

        return $activities
            ->sortByDesc('timestamp')
            ->take($limit)
            ->map(fn($a) => [
                'icon' => $a['icon'],
                'color' => $a['color'],
                'title' => $a['title'],
                'description' => $a['description'],
                'time' => $a['timestamp']->diffForHumans(),
            ])
            ->values()
            ->all();
    }

    protected function buildQuickAccessMenus(): array
    {
        /** @var \Modules\Security\Models\User $user */
        $user = Auth::user();

        $menus = [
            [
                'label' => 'Tambah Pegawai',
                'icon' => 'fa-user-plus',
                'color' => 'bg-sky-100 text-sky-950',
                'route' => 'master.employees.create',
                'permission' => 'employees.create',
            ],
            [
                'label' => 'Approval Cuti',
                'icon' => 'fa-calendar-check',
                'color' => 'bg-emerald-100 text-emerald-700',
                'route' => 'leave.requests.index',
                'permission' => 'leave-requests.view',
            ],
            [
                'label' => 'Presensi Darurat',
                'icon' => 'fa-triangle-exclamation',
                'color' => 'bg-amber-100 text-amber-700',
                'route' => 'attendance.emergency.index',
                'permission' => 'emergency-attendance.approve',
            ],
            [
                'label' => 'Rekap Absensi',
                'icon' => 'fa-clipboard-list',
                'color' => 'bg-indigo-100 text-indigo-700',
                'route' => 'attendance.attendances.index',
                'permission' => 'attendances.view',
            ],
            [
                'label' => 'Jenis Cuti',
                'icon' => 'fa-list-ul',
                'color' => 'bg-purple-100 text-purple-700',
                'route' => 'leave.leave-types.index',
                'permission' => 'leave-types.manage',
            ],
            [
                'label' => 'Lokasi Absensi',
                'icon' => 'fa-location-dot',
                'color' => 'bg-rose-100 text-rose-700',
                'route' => 'attendance.locations.index',
                'permission' => 'attendance-locations.view',
            ],
            [
                'label' => 'Manajemen User',
                'icon' => 'fa-user-gear',
                'color' => 'bg-slate-100 text-slate-700',
                'route' => 'security.users.index',
                'permission' => 'users.view',
            ],
            [
                'label' => 'Manajemen Role',
                'icon' => 'fa-shield-halved',
                'color' => 'bg-cyan-100 text-cyan-700',
                'route' => 'security.roles.index',
                'permission' => 'roles.view',
            ],
            [
                'label' => 'SP Candidate',
                'icon' => 'fa-triangle-exclamation',
                'color' => 'bg-amber-100 text-amber-700',
                'route' => 'schedule.sp-candidates.index',
                'permission' => 'sp-candidates.view',
            ],
        ];

        return collect($menus)
            ->filter(fn($menu) => $user->hasPermission($menu['permission']) && Route::has($menu['route']))
            ->values()
            ->all();
    }
}
