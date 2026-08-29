<?php

namespace Modules\Schedule\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Schedule\Contracts\Services\SpLetterServiceInterface;

class SpLetterController extends Controller
{
    public function __construct(
        protected SpLetterServiceInterface $spLetterService
    ) {}

    public function index(Request $request)
    {
        $employeeId = $request->user()->employee?->id;

        if (!$employeeId) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini tidak terhubung ke data pegawai.',
            ], 403);
        }

        $letters = $this->spLetterService->getMyLetters($employeeId);

        return response()->json([
            'success' => true,
            'message' => 'Daftar surat peringatan ditemukan.',
            'data' => $letters,
        ]);
    }

    public function show(Request $request, int $spLetter)
    {
        $employeeId = $request->user()->employee?->id;

        if (!$employeeId) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini tidak terhubung ke data pegawai.',
            ], 403);
        }

        $letter = $this->spLetterService->getMyLetterDetail($employeeId, $spLetter);

        return response()->json([
            'success' => true,
            'message' => 'Detail surat peringatan ditemukan.',
            'data' => [
                'id' => $letter->id,
                'sp_number' => $letter->sp_number,
                'file_url' => Storage::disk('public')->url($letter->file_path),
                'issued_at' => $letter->issued_at,
                'viewed_at' => $letter->viewed_at,
            ],
        ]);
    }

    public function unreadCount(Request $request)
    {
        $employeeId = $request->user()->employee?->id;

        if (!$employeeId) {
            return response()->json([
                'success' => true,
                'count' => 0,
            ]);
        }

        return response()->json([
            'success' => true,
            'count' => $this->spLetterService->unreadCount($employeeId),
        ]);
    }
}
