<?php

namespace Modules\Leave\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Leave\Contracts\Repositories\LeaveTypeRepositoryInterface;
use Modules\Leave\Contracts\Services\LeaveRequestServiceInterface;
use Modules\Leave\Models\LeaveRequest;

class LeaveController extends Controller
{
    public function __construct(
        protected LeaveRequestServiceInterface $leaveRequestService,
        protected LeaveTypeRepositoryInterface $leaveTypeRepository,
    ) {}

    public function index(Request $request)
    {
        $filters = [
            'status' => $request->get('status'),
            'leave_type_id' => $request->get('leave_type_id'),
            'year' => $request->get('year'),
            'employee_search' => $request->get('employee_search'),
        ];

        $leaveRequests = $this->leaveRequestService->allRequests($filters);
        $leaveTypes = $this->leaveTypeRepository->allActive();

        return view('leave::index', compact('leaveRequests', 'leaveTypes'));
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['employee', 'leaveType', 'approvals.approver']);

        $viewer = Auth::user()->employee;
        $canDecide = $viewer && $leaveRequest->isPendingApprovalBy($viewer);

        return view('leave::show', compact('leaveRequest', 'canDecide'));
    }

    public function decide(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'decision' => ['required', 'in:approve,reject'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $approver = Auth::user()->employee;

        $this->leaveRequestService->decide(
            $leaveRequest->id,
            $approver,
            $request->input('decision') === 'approve',
            $request->input('note'),
        );

        return redirect()
            ->route('leave.show', $leaveRequest)
            ->with('success', 'Keputusan berhasil disimpan.');
    }
}
