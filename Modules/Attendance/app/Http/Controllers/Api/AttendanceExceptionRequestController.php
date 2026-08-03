<?php

namespace Modules\Attendance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Attendance\Contracts\Services\AttendanceExceptionRequestServiceInterface;
use Modules\Attendance\DTOs\AttendanceExceptionRequestData;
use Modules\Attendance\Exceptions\AttendanceException;
use Modules\Attendance\Http\Requests\Api\StoreAttendanceExceptionRequestRequest;
use Modules\Shared\Traits\ApiResponse;

class AttendanceExceptionRequestController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AttendanceExceptionRequestServiceInterface $exceptionService
    ) {}

    public function store(StoreAttendanceExceptionRequestRequest $request)
    {
        try {
            $employee = $request->user()->employee;

            if (!$employee) {
                throw new AttendanceException('Akun Anda tidak terhubung dengan data pegawai.');
            }

            $attachmentPath = $request->hasFile('attachment')
                ? $request->file('attachment')->store('attendance/exceptions', 'public')
                : null;

            $exceptionRequest = $this->exceptionService->submit(AttendanceExceptionRequestData::fromArray([
                ...$request->validated(),
                'employee_id'      => $employee->id,
                'attachment_path'  => $attachmentPath,
            ]));

            return $this->success($exceptionRequest, 'Pengajuan berhasil dikirim.');
        } catch (AttendanceException $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    public function history(Request $request)
    {
        $employee = $request->user()->employee;

        if (!$employee) {
            return $this->error('Akun Anda tidak terhubung dengan data pegawai.', 422);
        }

        return $this->success($this->exceptionService->history($employee->id));
    }
}
