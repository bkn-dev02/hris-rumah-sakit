<?php

namespace Modules\Master\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Modules\Master\Http\Requests\StoreShiftVersionRequest;
use Modules\Master\Contracts\Services\ShiftServiceInterface;
use Modules\Master\DTOs\ShiftVersionData;

class ShiftController extends Controller
{
    public function __construct(
        protected ShiftServiceInterface $shiftService
    ) {}

    public function index()
    {
        $shifts = $this->shiftService->paginate(10);

        return view('master::shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('master::shifts.create');
    }

    public function store(StoreShiftVersionRequest $request)
    {
        $this->shiftService->createNewVersion(
            ShiftVersionData::fromArray($request->validated())
        );

        return redirect()
            ->route('master.shifts.index')
            ->with('success', 'Shift berhasil disimpan.');
    }

    public function history(string $code)
    {
        $history = $this->shiftService->historyByCode($code);

        return view('master::shifts.history', compact('history', 'code'));
    }

    public function editVersion(int $shift)
    {
        // dipakai untuk prefill form "buat versi baru" dari versi yang sedang aktif
        $shift = $this->shiftService->findById($shift);

        return view('master::shifts.create', compact('shift'));
    }

    public function destroy(int $shift)
    {
        try {
            $this->shiftService->delete($shift);
        } catch (QueryException $e) {
            return back()->with('error', 'Shift tidak dapat dihapus karena masih digunakan.');
        }

        return redirect()
            ->route('master.shifts.index')
            ->with('success', 'Shift berhasil dihapus.');
    }
}
