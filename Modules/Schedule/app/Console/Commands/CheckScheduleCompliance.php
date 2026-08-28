<?php

namespace Modules\Schedule\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Master\Models\Employee;
use Modules\Master\Models\Shift;
use Modules\Schedule\Contracts\Services\ScheduleServiceInterface;
use Modules\Schedule\Contracts\Services\SpCandidateServiceInterface;

class CheckScheduleCompliance extends Command
{
    protected $signature = 'schedule:check-compliance';

    protected $description = 'Cek kepatuhan kehadiran pegawai terhadap jadwal & shift, 30 menit setelah jam shift dimulai (SP Candidate checker)';

    public function handle(
        ScheduleServiceInterface $scheduleService,
        SpCandidateServiceInterface $spCandidateService
    ): int {
        $today = Carbon::today();
        $now = Carbon::now();

        $employees = Employee::where('is_active', true)->get();

        $checked = 0;

        foreach ($employees as $employee) {
            $resolved = $scheduleService->resolveEffectiveShift($employee->id, $today);

            if ($resolved['is_libur'] || $resolved['is_fallback'] || !$resolved['shift_id']) {
                continue;
            }

            $shift = Shift::find($resolved['shift_id']);
            if (!$shift) {
                continue;
            }

            $shiftStart = Carbon::parse($today->format('Y-m-d') . ' ' . $shift->start_time);
            $checkTime = $shiftStart->copy()->addMinutes(30);

            if ($now->lt($checkTime)) {
                continue;
            }

            $spCandidateService->runCheck($employee->id, $today, $shift->id);
            $checked++;
        }

        $this->info("Compliance check selesai. {$checked} pegawai diproses.");

        return self::SUCCESS;
    }
}
