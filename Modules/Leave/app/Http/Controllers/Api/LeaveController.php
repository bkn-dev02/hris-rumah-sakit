<?php

namespace Modules\Leave\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Modules\Leave\Contracts\Services\LeaveRequestServiceInterface;
use Modules\Leave\DTOs\LeaveRequestData;
use Modules\Leave\Http\Requests\Api\StoreLeaveRequestRequest;
use Modules\Shared\Traits\ApiResponse;
use Modules\Leave\Models\LeaveRequest;

class LeaveController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected LeaveRequestServiceInterface $leaveRequestService,
    ) {}

    public function statuses(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Status cuti ditemukan.',
            'data' => [
                [
                    'code' => 'pending',
                    'name' => 'Menunggu Persetujuan',
                    'label' => 'pending',
                ],
                [
                    'code' => 'approved',
                    'name' => 'Disetujui',
                    'label' => 'approved',
                ],
                [
                    'code' => 'rejected',
                    'name' => 'Ditolak',
                    'label' => 'rejected',
                ],
                [
                    'code' => 'cancelled',
                    'name' => 'Dibatalkan',
                    'label' => 'cancelled',
                ],
            ],
        ]);
    }

    public function myRequests(Request $request)
    {
        $employee = $this->resolveEmployee($request);

        $requests = $this->leaveRequestService->myRequests($employee);
        $requests->load(['leaveType', 'approvals.approver']);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pengajuan cuti ditemukan.',
            'data' => $requests->map(fn($r) => $this->transformLeaveRequest($r)),
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

        $leaveRequest->load(['leaveType', 'approvals.approver']);

        return response()->json([
            'success' => true,
            'message' => 'Detail pengajuan ditemukan.',
            'data' => $this->transformLeaveRequest($leaveRequest),
        ]);
    }

    protected function transformLeaveRequest(LeaveRequest $leaveRequest): array
    {
        return [
            'id' => $leaveRequest->id,
            'leave_type_id' => $leaveRequest->leave_type_id,
            'leave_type' => ['name' => $leaveRequest->leaveType->name],
            'start_date' => $leaveRequest->start_date->format('Y-m-d'),
            'end_date' => $leaveRequest->end_date->format('Y-m-d'),
            'total_days' => $leaveRequest->total_days,
            'reason' => $leaveRequest->reason,
            'attachment' => $leaveRequest->attachment,
            'status' => $leaveRequest->status,
            'approvals' => $leaveRequest->approvals->map(fn($a) => [
                'id' => $a->id,
                'sequence' => $a->sequence,
                'type' => $a->type,
                'status' => $a->status,
                'approver_name' => $a->approver->name,
                'approver_position' => $a->type === 'hrd'
                    ? 'HRD'
                    : ($a->approver->currentPosition()?->name ?? $a->typeLabel()),
                'decided_at' => $a->decided_at?->format('d M Y, H:i'),
                'note' => $a->note,
            ]),
        ];
    }

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
        Log::info('Debug attachment (mobile test)', [
            'has_file' => $request->hasFile('attachment'),
            'all_files' => $request->allFiles(),
            'content_type' => $request->header('Content-Type'),
        ]);

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
            'data' => $this->transformLeaveRequest($leaveRequest->load(['leaveType', 'approvals.approver'])),
        ], 201);
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
