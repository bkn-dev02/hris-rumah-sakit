<?php

namespace Modules\Attendance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Modules\Attendance\Contracts\Services\CheckInServiceInterface;
use Modules\Attendance\Http\Requests\StoreCheckInRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'location_id' => ['required', 'exists:attendance_locations,id'],
            'photo' => ['required', 'image', 'max:5120'],
            'note' => ['nullable', 'string'],
        ]);

        $employee = Auth::user()->employee;

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan.'
            ], 404);
        }

        $alreadyCheckIn = $this->checkInService
            ->findByEmployeeAndDate(
                $employee->id,
                now()->toDateString()
            );

        if ($alreadyCheckIn) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah check-in hari ini.'
            ], 422);
        }

        $photoPath = $request->file('photo')->store(
            'attendance/check-ins',
            'public'
        );

        $checkIn = $this->checkInService->create([
            'employee_id' => $employee->id,
            'checked_at' => now(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'photo' => $photoPath,
            'location_id' => $request->location_id,
            'distance_meters' => 0,
            'ip' => $request->ip(),
            'device' => $request->userAgent(),
            'note' => $request->note,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil.',
            'data' => $checkIn
        ], 201);
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
