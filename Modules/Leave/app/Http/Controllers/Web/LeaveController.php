<?php

namespace Modules\Leave\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Leave\Contracts\Repositories\LeaveTypeRepositoryInterface;
use Modules\Leave\Contracts\Services\LeaveRequestServiceInterface;
use Modules\Leave\Models\LeaveRequest;
use Modules\Leave\DTOs\LeaveRequestData;
use Modules\Leave\Http\Requests\StoreLeaveRequestRequest;

class LeaveController extends Controller
{
    public function __construct(
        protected LeaveRequestServiceInterface $leaveRequestService,
        protected LeaveTypeRepositoryInterface $leaveTypeRepository,
    ) {}

    public function index(Request $request)
    {
        $statuses = ['pending', 'approved', 'rejected', 'cancelled'];
        $filters = [
            'status' => $request->get('status'),
            'leave_type_id' => $request->get('leave_type_id'),
            'year' => $request->get('year'),
            'employee_search' => $request->get('employee_search'),
        ];

        $leaveRequests = $this->leaveRequestService->allRequests($filters);
        $leaveTypes = $this->leaveTypeRepository->allActive();
        $this->leaveRequestService->markPendingSeen();

        $statusCounts = LeaveRequest::query()
            ->selectRaw('status, count(*) as total')
            ->whereIn('status', $statuses)
            ->groupBy('status')
            ->pluck('total', 'status');
        $statusCounts['all'] = LeaveRequest::query()->count();

        return view('leave::index', compact('leaveRequests', 'leaveTypes', 'statusCounts'));
    }

    public function create(Request $request)
    {
        $employee = $request->user()->employee;

        abort_unless($employee, 403, 'Akun Anda tidak terhubung dengan data pegawai.');

        $leaveTypes = $this->leaveRequestService->getLeaveTypesWithQuota($employee);

        return view('leave::create', compact('employee', 'leaveTypes'));
    }

    public function store(StoreLeaveRequestRequest $request)
    {
        $employee = $request->user()->employee;

        abort_unless($employee, 403, 'Akun Anda tidak terhubung dengan data pegawai.');

        $attachmentPath = $request->hasFile('attachment')
            ? $request->file('attachment')->store('leave/attachments', 'public')
            : null;

        try {
            $this->leaveRequestService->submit(new LeaveRequestData(
                employeeId: $employee->id,
                leaveTypeId: $request->integer('leave_type_id'),
                startDate: $request->string('start_date')->toString(),
                endDate: $request->string('end_date')->toString(),
                reason: $request->string('reason')->toString(),
                attachment: $attachmentPath,
            ));
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('leave.index')
            ->with('success', 'Pengajuan cuti berhasil dikirim.');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $roleCodes = Auth::user()->roles()->pluck('code')->all();
        $isEmployeeOnly = in_array('pegawai', $roleCodes, true)
            && !array_intersect($roleCodes, ['super-admin', 'admin', 'hrd', 'direktur', 'kepala_unit']);

        if ($isEmployeeOnly && $leaveRequest->employee_id !== Auth::user()->employee?->id) {
            abort(404);
        }

        $leaveRequest->load(['employee', 'leaveType', 'approvals.approver']);

        $viewer = Auth::user()->employee;
        $canDecide = $viewer && $leaveRequest->isPendingApprovalBy($viewer);

        return view('leave::show', compact('leaveRequest', 'canDecide'));
    }

    public function downloadAttachment(LeaveRequest $leaveRequest)
    {
        abort_unless(
            $leaveRequest->attachment && Storage::disk('public')->exists($leaveRequest->attachment),
            404,
        );

        return response()->download(
            Storage::disk('public')->path($leaveRequest->attachment),
            basename($leaveRequest->attachment),
        );
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
