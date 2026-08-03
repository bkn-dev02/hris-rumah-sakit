<?php

namespace Modules\Attendance\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Attendance\Contracts\Services\AttendanceExceptionRequestServiceInterface;
use Modules\Attendance\Exceptions\AttendanceException;
use Modules\Attendance\Http\Requests\RejectAttendanceExceptionRequestRequest;

class AttendanceExceptionRequestController extends Controller
{
    public function __construct(
        protected AttendanceExceptionRequestServiceInterface $exceptionService
    ) {}

    public function index(Request $request)
    {
        $approvalStatus = $request->get('status', 'pending');

        $requests = $this->exceptionService->paginate(15, $approvalStatus);

        return view('attendance::exception-requests.index', compact('requests', 'approvalStatus'));
    }

    public function show(int $exceptionRequest)
    {
        $exceptionRequest = $this->exceptionService->findById($exceptionRequest);

        return view('attendance::exception-requests.show', compact('exceptionRequest'));
    }

    public function approve(int $exceptionRequest)
    {
        try {
            $this->exceptionService->approve($exceptionRequest, Auth::id());
        } catch (AttendanceException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('attendance.exception-requests.index')
            ->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject(RejectAttendanceExceptionRequestRequest $request, int $exceptionRequest)
    {
        try {
            $this->exceptionService->reject($exceptionRequest, $request->validated('rejection_reason'), Auth::id());
        } catch (AttendanceException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('attendance.exception-requests.index')
            ->with('success', 'Pengajuan berhasil ditolak.');
    }
}
