<?php

namespace Modules\Attendance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Attendance\Contracts\Repositories\AttendanceStatusRepositoryInterface;

class AttendanceStatusController extends Controller
{
    public function __construct(
        protected AttendanceStatusRepositoryInterface $statusRepository,
    ) {}

    public function index(Request $request)
    {
        $statuses = $this->statusRepository
            ->all()
            ->filter(fn($status) => (bool) $status->is_active)
            ->map(fn($status) => [
                'id' => $status->id,
                'code' => $status->code,
                'name' => $status->name,
                'category' => $status->category,
                'determination_type' => $status->determination_type,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Status kehadiran ditemukan.',
            'data' => $statuses->values(),
        ]);
    }

    public function leaveStatus(Request $request)
    {
        $status = $this->statusRepository->findByCode('CUTI')
            ?? $this->statusRepository->findByCode('LEAVE')
            ?? $this->statusRepository->create([
                'code' => 'CUTI',
                'name' => 'Cuti',
                'category' => 'exception',
                'determination_type' => 'manual',
                'is_active' => true,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Status cuti ditemukan.',
            'data' => [
                'id' => $status->id,
                'code' => $status->code,
                'name' => $status->name,
                'category' => $status->category,
                'determination_type' => $status->determination_type,
            ],
        ]);
    }
}
