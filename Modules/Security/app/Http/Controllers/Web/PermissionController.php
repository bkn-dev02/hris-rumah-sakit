<?php

namespace Modules\Security\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Modules\Security\Contracts\Services\PermissionServiceInterface;
use Modules\Security\Http\Requests\StorePermissionRequest;
use Modules\Security\Http\Requests\UpdatePermissionRequest;

class PermissionController extends Controller
{
    public function __construct(
        protected PermissionServiceInterface $permissionService
    ) {}

    public function index(Request $request)
    {
        $permissions = $this->permissionService->paginate(15, $request->get('module'));
        $modules = $this->permissionService->modules();

        return view('security::permissions.index', compact('permissions', 'modules'));
    }

    public function create()
    {
        $modules = $this->permissionService->modules();

        return view('security::permissions.create', compact('modules'));
    }

    public function store(StorePermissionRequest $request)
    {
        $this->permissionService->create($request->validated());

        return redirect()
            ->route('security.permissions.index')
            ->with('success', 'Permission berhasil ditambahkan.');
    }

    public function edit(int $permission)
    {
        $permission = $this->permissionService->findById($permission);
        $modules = $this->permissionService->modules();

        return view('security::permissions.edit', compact('permission', 'modules'));
    }

    public function update(UpdatePermissionRequest $request, int $permission)
    {
        $this->permissionService->update($permission, $request->validated());

        return redirect()
            ->route('security.permissions.index')
            ->with('success', 'Permission berhasil diperbarui.');
    }

    public function destroy(int $permission)
    {
        try {
            $this->permissionService->delete($permission);
        } catch (QueryException $e) {
            return back()->with('error', 'Permission tidak dapat dihapus karena masih digunakan role.');
        }

        return redirect()
            ->route('security.permissions.index')
            ->with('success', 'Permission berhasil dihapus.');
    }
}
