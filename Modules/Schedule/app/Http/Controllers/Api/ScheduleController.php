<?php

namespace Modules\Schedule\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Schedule\Contracts\Services\ScheduleServiceInterface;

class ScheduleController extends Controller
{
    public function __construct(
        protected ScheduleServiceInterface $scheduleService
    ) {}

    public function myResolvedShift(Request $request)
    {
        $date = $request->filled('date')
            ? Carbon::parse($request->input('date'))
            : Carbon::today();

        $resolved = $this->scheduleService->resolveEffectiveShift(
            $request->user()->employee->id,
            $date
        );

        return response()->json(['data' => $resolved]);
    }
}
