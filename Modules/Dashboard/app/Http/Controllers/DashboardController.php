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
use Modules\Attendance\Models\AttendanceExceptionRequest;
use Modules\Schedule\Models\SpCandidate;
use Modules\Master\Models\Department;
use Modules\Master\Models\Shift;
use Modules\Schedule\Contracts\Services\ScheduleServiceInterface;
use Modules\Dashboard\Exceptions\EmployeeNotLinkedException;

class DashboardController extends Controller
{
    /**
     * Role codes yang berhak melihat section organisasi/KPI.
     */
    protected const ELEVATED_ROLES = ['super-admin', 'admin', 'hrd', 'direktur', 'kepala_unit'];

    /**
     * Urutan tampil section.
     */
    protected const SECTION_ORDER = ['pegawai', 'hrd'];

    /**
     * Pemetaan key section -> partial view yang merendernya.
     */
    protected const SECTION_VIEWS = [
        'pegawai' => 'dashboard::partials.employee-section',
        'hrd' => 'dashboard::partials.hrd-section',
    ];

    public function __construct(
        protected AttendanceServiceInterface $attendanceService,
        protected EmployeeServiceInterface $employeeService,
    ) {}

    public function index(Request $request)
    {
        $actor = $request->user();

        // 1 query untuk semua kode role
        $roleCodes = $actor->roles()->pluck('code')->all();

        $built = [];

        if (in_array('pegawai', $roleCodes, true) && $actor->employee) {
            $built['pegawai'] = $this->buildPegawaiSection($actor->employee);
        }

        if (array_intersect($roleCodes, self::ELEVATED_ROLES)) {
            $built['hrd'] = $this->buildHrdSection($request, $roleCodes);
        }

        $sections = [];
        foreach (self::SECTION_ORDER as $key) {
            if (isset($built[$key])) {
                $sections[$key] = $built[$key];
            }
        }

        if (empty($sections)) {
            $isDeactivated = Employee::onlyTrashed()
                ->where('user_id', $actor->id)
                ->exists();

            if ($isDeactivated) {
                throw new EmployeeNotLinkedException(
                    'Akun Anda telah dinonaktifkan. Jika ini keliru, silakan hubungi Admin atau HRD untuk mengaktifkan kembali.',
                    'Akun Dinonaktifkan'
                );
            }

            throw new EmployeeNotLinkedException();
        }

        return view('dashboard::home', [
            'sections'     => $sections,
            'sectionViews' => self::SECTION_VIEWS,
        ]);
    }

    /**
     * Bangun payload untuk section "pegawai" — ringkasan personal.
     */
    protected function buildPegawaiSection(Employee $employee): array
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        return [
            'employee'      => $employee,
            'attendance'    => $this->attendanceService->todayForDisplay($employee->id),
            'monthlyStats'  => $this->attendanceService->getMonthlyPersonalSummary(
                $employee->id,
                $today->year,
                $today->month
            ),
            'todayShift'    => $this->resolveShiftInfo($employee->id, $today),
            'tomorrowShift' => $this->resolveShiftInfo($employee->id, $tomorrow),
        ];
    }

    /**
     * Bangun payload untuk section "hrd" — KPI & ringkasan organisasi.
     */
    protected function buildHrdSection(Request $request, array $roleCodes): array
    {
        $scope = $this->resolveDepartmentScope($request, $roleCodes);
        $employeeIds = $scope['employee_ids'];
        $today = Carbon::today();

        $total = count($employeeIds);
        $active = Employee::query()
            ->whereIn('id', $employeeIds)
            ->where('is_active', true)
            ->count();
        $present = Attendance::query()
            ->whereIn('employee_id', $employeeIds)
            ->whereDate('work_date', $today)
            ->count();
        $onLeave = AttendanceExceptionRequest::query()
            ->whereIn('employee_id', $employeeIds)
            ->whereDate('work_date', $today)
            ->approved()
            ->count();
        $summary = [
            'total' => $total,
            'aktif' => $active,
            'nonaktif' => $total - $active,
            'present' => $present,
            'on_leave' => $onLeave,
            'absent' => max($total - $present - $onLeave, 0),
        ];

        $stats = [
            'total'    => (int) ($summary['total'] ?? 0),
            'aktif'    => (int) ($summary['aktif'] ?? 0),
            'nonaktif' => (int) ($summary['nonaktif'] ?? 0),
            'present'  => (int) ($summary['present'] ?? 0),
            'late'     => (int) ($this->lateTodayCount($employeeIds) ?? 0),
            'absent'   => (int) ($summary['absent'] ?? 0),
            'leave'    => (int) ($summary['on_leave'] ?? 0),
        ];

        $stats['present_pct'] = $stats['total'] > 0 ? round(($stats['present'] / $stats['total']) * 100) : 0;
        $stats['late_pct']    = $stats['present'] > 0 ? round(($stats['late'] / $stats['present']) * 100) : 0;
        $stats['absent_pct']  = $stats['total'] > 0 ? round(($stats['absent'] / $stats['total']) * 100) : 0;

        return [
            'stats'                   => $stats,
            'chartData'               => $this->buildChartData($employeeIds, $scope['is_global']),
            'departmentDistribution'  => $scope['show_distribution']
                ? $this->buildDepartmentDistribution($employeeIds)
                : [],
            'showDepartmentDistribution' => $scope['show_distribution'],
            'recentActivities'        => $this->buildRecentActivities($employeeIds),
            'quickAccessMenus'        => $this->buildQuickAccessMenus(),
            'pendingLeaveCount'       => LeaveRequest::query()
                ->whereIn('employee_id', $employeeIds)
                ->where('status', 'pending')
                ->count(),
            'pendingEmergencyCount'   => CheckIn::query()
                ->whereIn('employee_id', $employeeIds)
                ->where('type', 'emergency')
                ->where('emergency_status', 'pending')
                ->count(),
            'pendingSpCandidateCount' => SpCandidate::query()
                ->whereIn('employee_id', $employeeIds)
                ->whereIn('status', ['candidate', 'pending_decision'])
                ->count(),
            'departmentScope'         => $scope['department'],
        ];
    }

    protected function resolveDepartmentScope(Request $request, array $roleCodes): array
    {
        $isGlobal = count(array_intersect($roleCodes, ['super-admin', 'admin', 'hrd', 'direktur'])) > 0;

        if ($isGlobal) {
            return [
                'employee_ids' => Employee::withTrashed()->pluck('id')->all(),
                'show_distribution' => true,
                'department' => null,
                'is_global' => true,
            ];
        }

        $department = $request->user()->employee?->currentDepartment();

        if (!$department) {
            return [
                'employee_ids' => [],
                'show_distribution' => false,
                'department' => null,
                'is_global' => false,
            ];
        }

        $departmentIds = $this->descendantDepartmentIds($department);
        $employeeIds = Employee::withTrashed()
            ->whereHas('placements', fn($query) => $query
                ->active()
                ->whereIn('department_id', $departmentIds))
            ->pluck('id')
            ->all();

        return [
            'employee_ids' => $employeeIds,
            'show_distribution' => count($departmentIds) > 1,
            'department' => $department,
            'is_global' => false,
        ];
    }

    protected function descendantDepartmentIds(Department $department): array
    {
        $ids = [$department->id];
        $children = Department::whereIn('parent_id', $ids)->pluck('id')->all();

        while ($children) {
            $children = array_values(array_diff($children, $ids));
            if (!$children) {
                break;
            }

            $ids = array_merge($ids, $children);
            $children = Department::whereIn('parent_id', $children)->pluck('id')->all();
        }

        return array_values(array_unique($ids));
    }

    protected function buildDepartmentDistribution(array $employeeIds): array
    {
        $employees = Employee::query()
            ->whereIn('id', $employeeIds)
            ->where('is_active', true)
            ->get();
        $total = $employees->count();

        return $employees
            ->groupBy(fn($employee) => $employee->currentDepartment()?->name ?? 'Belum Ditempatkan')
            ->map(fn($group, $name) => [
                'name' => $name,
                'total' => $group->count(),
                'percent' => $total > 0 ? round(($group->count() / $total) * 100) . '%' : '0%',
            ])
            ->sortByDesc('total')
            ->values()
            ->all();
    }

    protected function resolveShiftInfo(int $employeeId, Carbon $date): array
    {
        $resolved = app(ScheduleServiceInterface::class)
            ->resolveEffectiveShift($employeeId, $date->copy());

        if (!empty($resolved['is_libur']) || !$resolved['shift_id']) {
            return [
                'is_libur'   => (bool) ($resolved['is_libur'] ?? false),
                'name'       => null,
                'start_time' => null,
                'end_time'   => null,
            ];
        }

        $shift = Shift::find($resolved['shift_id']);

        return [
            'is_libur'   => false,
            'name'       => $shift?->name,
            'start_time' => $shift?->start_time ? Carbon::parse($shift->start_time)->format('H:i') : null,
            'end_time'   => $shift?->end_time ? Carbon::parse($shift->end_time)->format('H:i') : null,
        ];
    }

    protected function buildChartData(array $employeeIds, bool $isGlobal): array
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
            $count = Attendance::query()
                ->whereIn('employee_id', $employeeIds)
                ->whereDate('work_date', $date)
                ->count();
            $days[] = [
                'day' => $date->translatedFormat('D'),
                'value' => (int) $count,
            ];
            $maxCount = max($maxCount, $count);
        }

        $hasRealData = collect($days)->contains(fn($item) => (int) $item['value'] > 0);

        if (! $hasRealData) {
            return $isGlobal ? $base : collect($days)->map(fn($item) => [
                'day' => $item['day'],
                'height' => '18%',
                'value' => '0',
            ])->all();
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

    protected function lateTodayCount(array $employeeIds): ?int
    {
        $late = Attendance::query()
            ->whereIn('employee_id', $employeeIds)
            ->whereDate('work_date', today())
            ->whereNotNull('attendance_status_id')
            ->whereHas('status', fn($query) => $query->where('code', 'TERLAMBAT'))
            ->count();

        return $late > 0 ? $late : null;
    }

    protected function buildRecentActivities(array $employeeIds, int $limit = 10): array
    {
        $activities = collect();

        Employee::query()
            ->whereIn('id', $employeeIds)
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
            ->whereIn('employee_id', $employeeIds)
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
            ->whereIn('employee_id', $employeeIds)
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

        SpCandidate::query()
            ->with(['employee' => fn($q) => $q->withTrashed()])
            ->whereIn('employee_id', $employeeIds)
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
