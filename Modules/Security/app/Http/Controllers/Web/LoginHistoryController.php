<?php

namespace Modules\Security\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Security\Contracts\Services\LoginHistoryServiceInterface;

class LoginHistoryController extends Controller
{
    public function __construct(
        protected LoginHistoryServiceInterface $loginHistoryService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'start_date', 'end_date']);

        $histories = $this->loginHistoryService->paginate(15, $filters);

        return view('security::login-histories.index', compact('histories'));
    }
}
