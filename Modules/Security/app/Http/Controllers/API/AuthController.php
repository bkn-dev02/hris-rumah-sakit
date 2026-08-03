<?php

namespace Modules\Security\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Security\Http\Requests\Api\LoginRequest;
use Modules\Security\Services\UserService;
use Modules\Shared\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected UserService $userService
    ) {}

    public function login(LoginRequest $request)
    {
        $user = $this->userService->findByUsername($request->username);

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('Username atau password salah.', 401);
        }

        if (!$user->is_active) {
            return $this->error('Akun tidak aktif. Hubungi administrator.', 403);
        }

        $user->forceFill(['last_login_at' => now()])->save();

        $token = $user->createToken('mobile')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user'  => [
                'id'       => $user->id,
                'username' => $user->username,
                'email'    => $user->email,
            ],
        ], 'Login berhasil.');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logout berhasil.');
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return $this->success([
            'id'         => $user->id,
            'username'   => $user->username,
            'email'      => $user->email,
            'is_active'  => $user->is_active,
        ]);
    }
}
