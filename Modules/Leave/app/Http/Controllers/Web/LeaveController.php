<?php

namespace Modules\Leave\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Leave\Contracts\Repositories\LeaveTypeRepositoryInterface;
use Modules\Leave\Contracts\Services\LeaveRequestServiceInterface;

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
}
