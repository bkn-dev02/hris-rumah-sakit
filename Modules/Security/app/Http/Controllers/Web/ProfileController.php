<?php

namespace Modules\Security\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Security\Contracts\Services\LoginHistoryServiceInterface;
use Modules\Security\Http\Requests\UpdatePasswordRequest;
use Modules\Security\Services\UserService;

class ProfileController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected LoginHistoryServiceInterface $loginHistoryService
    ) {}

    public function show()
    {
        $user = Auth::user()->load(['roles.permissions', 'employee']);

        $recentLogins = $this->loginHistoryService->paginate(5, [
            'user_id' => $user->id,
            'status'  => 'success',
        ]);

        return view('security::profile.show', compact('user', 'recentLogins'));
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $this->userService->updatePassword(Auth::user(), $request->validated('password'));

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
