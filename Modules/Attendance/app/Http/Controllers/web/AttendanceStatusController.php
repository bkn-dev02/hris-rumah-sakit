<?php

namespace Modules\Attendance\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Modules\Attendance\Contracts\Services\AttendanceStatusServiceInterface;
use Modules\Attendance\Http\Requests\StoreAttendanceStatusRequest;
use Modules\Attendance\Http\Requests\UpdateAttendanceStatusRequest;

class AttendanceStatusController extends Controller
{
    public function __construct(
        protected AttendanceStatusServiceInterface $statusService
    ) {}

    public function index()
    {
        $statuses = $this->statusService->paginate(10);

        return view('attendance::statuses.index', compact('statuses'));
    }

    public function create()
    {
        return view('attendance::statuses.create');
    }

    public function store(StoreAttendanceStatusRequest $request)
    {
        $this->statusService->create($request->validated());

        return redirect()
            ->route('attendance.statuses.index')
            ->with('success', 'Status kehadiran berhasil ditambahkan.');
    }

    public function edit(int $attendance_status)
    {
        $status = $this->statusService->findById($attendance_status);

        return view('attendance::statuses.edit', ['status' => $status]);
    }

    public function update(UpdateAttendanceStatusRequest $request, int $attendance_status)
    {
        $this->statusService->update($attendance_status, $request->validated());

        return redirect()
            ->route('attendance.statuses.index')
            ->with('success', 'Status kehadiran berhasil diperbarui.');
    }

    public function destroy(int $attendance_status)
    {
        try {
            $this->statusService->delete($attendance_status);
        } catch (QueryException $e) {
            return back()->with('error', 'Status tidak dapat dihapus karena masih digunakan.');
        }

        return redirect()
            ->route('attendance.statuses.index')
            ->with('success', 'Status kehadiran berhasil dihapus.');
    }
}
