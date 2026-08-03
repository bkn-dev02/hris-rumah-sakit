<?php

namespace Modules\Attendance\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Modules\Attendance\Contracts\Services\AttendanceLocationServiceInterface;
use Modules\Attendance\Http\Requests\StoreAttendanceLocationRequest;
use Modules\Attendance\Http\Requests\UpdateAttendanceLocationRequest;

class AttendanceLocationController extends Controller
{
    public function __construct(
        protected AttendanceLocationServiceInterface $locationService
    ) {}

    public function index()
    {
        $locations = $this->locationService->paginate(10);

        return view('attendance::locations.index', compact('locations'));
    }

    public function create()
    {
        return view('attendance::locations.create');
    }

    public function store(StoreAttendanceLocationRequest $request)
    {
        $this->locationService->create($request->validated());

        return redirect()
            ->route('attendance.locations.index')
            ->with('success', 'Lokasi absensi berhasil ditambahkan.');
    }

    public function edit(int $location)
    {
        $location = $this->locationService->findById($location);

        return view('attendance::locations.edit', compact('location'));
    }

    public function update(UpdateAttendanceLocationRequest $request, int $location)
    {
        $this->locationService->update($location, $request->validated());

        return redirect()
            ->route('attendance.locations.index')
            ->with('success', 'Lokasi absensi berhasil diperbarui.');
    }

    public function destroy(int $location)
    {
        try {
            $this->locationService->delete($location);
        } catch (QueryException $e) {
            return back()->with('error', 'Lokasi tidak dapat dihapus karena masih digunakan.');
        }

        return redirect()
            ->route('attendance.locations.index')
            ->with('success', 'Lokasi absensi berhasil dihapus.');
    }
}
