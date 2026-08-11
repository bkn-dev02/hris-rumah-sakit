<?php

namespace Modules\Leave\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Modules\Leave\Contracts\Repositories\LeaveTypeRepositoryInterface;
use Modules\Leave\Http\Requests\StoreLeaveTypeRequest;
use Modules\Leave\Http\Requests\UpdateLeaveTypeRequest;
use Modules\Leave\Models\LeaveType;

class LeaveTypeController extends Controller
{
    public function __construct(
        protected LeaveTypeRepositoryInterface $leaveTypeRepository
    ) {}

    public function index(Request $request)
    {
        $leaveTypes = LeaveType::query()
            ->orderBy('name')
            ->paginate(15);

        return view('leave::leave-types.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('leave::leave-types.create');
    }

    public function store(StoreLeaveTypeRequest $request)
    {
        LeaveType::query()->create($request->validated());

        return redirect()
            ->route('leave.leave-types.index')
            ->with('success', 'Jenis cuti berhasil ditambahkan.');
    }

    public function edit(LeaveType $leaveType)
    {
        return view('leave::leave-types.edit', compact('leaveType'));
    }

    public function update(UpdateLeaveTypeRequest $request, LeaveType $leaveType)
    {
        $leaveType->update($request->validated());

        return redirect()
            ->route('leave.leave-types.index')
            ->with('success', 'Jenis cuti berhasil diperbarui.');
    }

    public function destroy(LeaveType $leaveType)
    {
        try {
            $leaveType->delete();
        } catch (QueryException $e) {
            return back()->with('error', 'Jenis cuti tidak dapat dihapus karena masih digunakan.');
        }

        return redirect()
            ->route('leave.leave-types.index')
            ->with('success', 'Jenis cuti berhasil dihapus.');
    }
}
