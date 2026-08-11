<?php

namespace Modules\Leave\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Modules\Leave\Contracts\Repositories\EmployeeLeaveQuotaRepositoryInterface;
use Modules\Leave\Http\Requests\UpdateEmployeeLeaveQuotasRequest;
use Modules\Master\Models\Employee;

class EmployeeLeaveQuotaController extends Controller
{
    public function __construct(
        protected EmployeeLeaveQuotaRepositoryInterface $employeeLeaveQuotaRepository
    ) {}

    public function updateForEmployee(UpdateEmployeeLeaveQuotasRequest $request, Employee $employee)
    {
        $validated = $request->validated();

        foreach ($validated['quotas'] as $quota) {
            $this->employeeLeaveQuotaRepository->upsert(
                $employee->id,
                $quota['leave_type_id'],
                $validated['year'],
                $quota['quota_days']
            );
        }

        return redirect()
            ->route('master.employees.edit', $employee->slug)
            ->with('success', 'Kuota cuti karyawan berhasil diperbarui.')
            ->with('quota_year', $validated['year']);
    }
}
