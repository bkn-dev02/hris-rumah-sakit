<?php

namespace Modules\Attendance\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Modules\Attendance\Contracts\Services\CheckInServiceInterface;
use Modules\Attendance\Http\Requests\StoreCheckInRequest;

class CheckInController extends Controller
{
    public function __construct(
        protected CheckInServiceInterface $checkInService
    ) {}

    public function index()
    {
        $checkIns = $this->checkInService->paginate(15);

        return view('attendance::check-ins.index', compact('checkIns'));
    }

    public function create()
    {
        return view('attendance::check-ins.create');
    }

    public function store(StoreCheckInRequest $request)
    {
        $this->checkInService->create($request->validated());

        return redirect()
            ->route('attendance.check-ins.index')
            ->with('success', 'Check-in berhasil disimpan.');
    }

    public function edit(int $check_in)
    {
        $checkIn = $this->checkInService->findById($check_in);

        return view('attendance::check-ins.edit', compact('checkIn'));
    }

    public function update(StoreCheckInRequest $request, int $check_in)
    {
        $this->checkInService->update($check_in, $request->validated());

        return redirect()
            ->route('attendance.check-ins.index')
            ->with('success', 'Check-in berhasil diperbarui.');
    }

    public function destroy(int $check_in)
    {
        try {
            $this->checkInService->delete($check_in);
        } catch (QueryException $e) {
            return back()->with('error', 'Check-in tidak dapat dihapus.');
        }

        return redirect()
            ->route('attendance.check-ins.index')
            ->with('success', 'Check-in berhasil dihapus.');
    }
}
