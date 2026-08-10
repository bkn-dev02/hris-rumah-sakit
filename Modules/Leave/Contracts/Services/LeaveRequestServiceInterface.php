<?php

namespace Modules\Leave\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Leave\DTOs\LeaveRequestData;
use Modules\Leave\Models\LeaveRequest;
use Modules\Master\Models\Employee;

interface LeaveRequestServiceInterface
{
    /** Daftar jenis cuti aktif + sisa kuota tahun berjalan untuk karyawan ini */
    public function getLeaveTypesWithQuota(Employee $employee): Collection;

    /** Ajukan cuti baru. Melempar exception kalau kuota tidak cukup / tanggal bentrok. */
    public function submit(LeaveRequestData $data): LeaveRequest;

    /** Riwayat pengajuan cuti milik karyawan ini */
    public function myRequests(Employee $employee): Collection;

    /** Detail satu pengajuan, hanya kalau milik karyawan ini */
    public function findMyRequest(int $id, Employee $employee): ?LeaveRequest;
}
