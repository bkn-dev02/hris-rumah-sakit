<?php

namespace Modules\Security\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Modules\Security\Http\Requests\StoreUserRequest;
use Modules\Security\Http\Requests\UpdateUserRequest;
use Modules\Security\Services\UserService;
use Illuminate\Http\Request;
use Modules\Security\Services\RoleService;

class UserController extends Controller
{

    public function __construct(
        protected UserService $userService,
        protected RoleService $roleService
    ) {}

    public function index(Request $request)
    {
        $users = $this->userService->paginate(10, trashed: $request->boolean('trashed'));

        return view('security::users.index', compact('users'));
    }

    public function create()
    {
        $roles = $this->roleService->getAll();

        return view('security::users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $this->userService->create(
            $request->validated()
        );

        return redirect()
            ->route('security.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(string $slug)
    {
        $user = $this->userService->findBySlug($slug);

        return view('security::users.show', compact('user'));
    }

    public function edit(string $slug)
    {
        $user = $this->userService->findBySlug($slug);
        $roles = $this->roleService->getAll();
        $assignedRoleIds = $user->roles->pluck('id')->all();

        return view('security::users.edit', compact('user', 'roles', 'assignedRoleIds'));
    }

    public function update(
        UpdateUserRequest $request,
        string $slug
    ) {
        $this->userService->update(
            $slug,
            $request->validated()
        );

        return redirect()
            ->route('security.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(string $slug)
    {
        $this->userService->delete($slug);

        return redirect()
            ->route('security.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function restore(string $slug)
    {
        $this->userService->restore($slug);

        return redirect()
            ->route('security.users.index')
            ->with('success', 'User berhasil dipulihkan.');
    }

    public function forceDelete(string $slug)
    {
        $this->userService->forceDelete($slug);

        return redirect()
            ->route('security.users.index')
            ->with('success', 'User berhasil dihapus permanen.');
    }
}
