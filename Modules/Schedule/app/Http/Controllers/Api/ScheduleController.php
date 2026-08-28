<?php

namespace Modules\Schedule\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Schedule\Contracts\Services\ScheduleServiceInterface;

class ScheduleController extends Controller
{
    public function __construct(
        protected ScheduleServiceInterface $scheduleService
    ) {}

    public function myMonth(Request $request)
    {
        $employee = $request->user()->employee;

        if (! $employee) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak terhubung dengan data pegawai.',
            ], 422);
        }

        $year = $request->integer('year') ?: now()->year;
        $month = $request->integer('month') ?: now()->month;

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $schedule = $this->scheduleService->getEmployeeSchedule($employee->id, $startDate, $endDate);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal ditemukan.',
            'data' => $schedule,
        ]);
    }
}
