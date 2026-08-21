<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Attendance\Contracts\Services\AttendanceServiceInterface;
use Modules\Attendance\Models\Attendance;

class DashboardController extends Controller
{
    public function __construct(
        protected AttendanceServiceInterface $attendanceService
    ) {}

    public function index()
    {
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

        return view('dashboard::index', compact('stats', 'chartData'));
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
}
