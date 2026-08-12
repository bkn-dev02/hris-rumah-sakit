<?php

namespace Modules\Leave\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RuntimeException;
use Modules\Leave\Contracts\Services\LeaveRequestServiceInterface;
use Modules\Leave\DTOs\LeaveRequestData;
use Modules\Leave\Http\Requests\Api\StoreLeaveRequestRequest;
use Modules\Shared\Traits\ApiResponse;

class LeaveController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected LeaveRequestServiceInterface $leaveRequestService,
    ) {}

    public function leaveTypes(Request $request)
    {
        $employee = $this->resolveEmployee($request);

        $leaveTypes = $this->leaveRequestService->getLeaveTypesWithQuota($employee);

        return response()->json([
            'success' => true,
            'message' => 'Jenis cuti ditemukan.',
            'data' => $leaveTypes->map(fn($lt) => [
                'id' => $lt->id,
                'code' => $lt->code,
                'name' => $lt->name,
                'requires_quota' => $lt->requires_quota,
                'remaining_quota' => $lt->remaining_quota,
            ]),
        ]);
    }

    public function store(StoreLeaveRequestRequest $request)
    {
        $employee = $this->resolveEmployee($request);

        $attachmentPath = $request->hasFile('attachment')
            ? $request->file('attachment')->store('leave/attachments', 'public')
            : null;

        try {
            $leaveRequest = $this->leaveRequestService->submit(new LeaveRequestData(
                employeeId: $employee->id,
                leaveTypeId: $request->integer('leave_type_id'),
                startDate: $request->string('start_date'),
                endDate: $request->string('end_date'),
                reason: $request->string('reason'),
                attachment: $attachmentPath,
            ));
        } catch (RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan cuti berhasil dikirim.',
            'data' => $leaveRequest->load('leaveType'),
        ], 201);
    }

    public function myRequests(Request $request)
    {
        $employee = $this->resolveEmployee($request);

        $requests = $this->leaveRequestService->myRequests($employee);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pengajuan cuti ditemukan.',
            'data' => $requests->load('leaveType'),
        ]);
    }

    public function show(Request $request, int $id)
    {
        $employee = $this->resolveEmployee($request);

        $leaveRequest = $this->leaveRequestService->findMyRequest($id, $employee);

        if (! $leaveRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan cuti tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pengajuan ditemukan.',
            'data' => $leaveRequest->load('leaveType', 'supervisor', 'hrApprover'),
        ]);
    }

    protected function resolveEmployee(Request $request)
    {
        $employee = $request->user()->employee;

        if (! $employee) {
            abort(response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak terhubung dengan data pegawai.',
            ], 422));
        }

        return $employee;
    }

    public function cancel(Request $request, int $id)
    {
        $employee = $this->resolveEmployee($request);

        try {
            $leaveRequest = $this->leaveRequestService->cancel($id, $employee);
        } catch (RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan cuti berhasil dibatalkan.',
            'data' => $leaveRequest,
        ]);
    }
}
