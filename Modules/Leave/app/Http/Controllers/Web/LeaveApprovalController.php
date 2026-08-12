<?php

namespace Modules\Leave\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RuntimeException;
use Modules\Leave\Contracts\Services\LeaveRequestServiceInterface;

class LeaveApprovalController extends Controller
{
    public function __construct(
        protected LeaveRequestServiceInterface $leaveRequestService,
    ) {}

    public function supervisorIndex(Request $request)
    {
        $employee = $this->resolveEmployee($request);
        $leaveRequests = $this->leaveRequestService->pendingForSupervisor($employee);

        return view('leave::approvals.supervisor', compact('leaveRequests'));
    }

    public function decideBySupervisor(Request $request, int $leaveRequestId)
    {
        $request->validate([
            'decision' => ['required', 'in:approve,reject'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $employee = $this->resolveEmployee($request);

        try {
            $this->leaveRequestService->decideBySupervisor(
                $leaveRequestId,
                $employee,
                $request->decision === 'approve',
                $request->note
            );
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('leave.approvals.supervisor')
            ->with('success', 'Pengajuan cuti berhasil diproses.');
    }

    public function hrIndex()
    {
        $leaveRequests = $this->leaveRequestService->pendingForHr();

        return view('leave::approvals.hr', compact('leaveRequests'));
    }

    public function decideByHr(Request $request, int $leaveRequestId)
    {
        $request->validate([
            'decision' => ['required', 'in:approve,reject'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $employee = $this->resolveEmployee($request);

        try {
            $this->leaveRequestService->decideByHr(
                $leaveRequestId,
                $employee,
                $request->decision === 'approve',
                $request->note
            );
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('leave.approvals.hr')
            ->with('success', 'Pengajuan cuti berhasil diproses.');
    }

    protected function resolveEmployee(Request $request)
    {
        $employee = $request->user()->employee;

        if (! $employee) {
            abort(422, 'Akun Anda tidak terhubung dengan data pegawai.');
        }

        return $employee;
    }
}
