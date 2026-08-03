<?php

namespace Modules\Security\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Modules\Security\Http\Requests\StoreRoleRequest;
use Modules\Security\Http\Requests\UpdateRoleRequest;
use Modules\Security\Models\Permission;
use Modules\Security\Services\RoleService;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService
    ) {}

    public function index()
    {
        $roles = $this->roleService->paginate(10);

        return view('security::roles.index', compact('roles'));
    }

    public function create()
    {
        $permissionGroups = $this->groupedPermissions();

        return view('security::roles.create', compact('permissionGroups'));
    }

    public function store(StoreRoleRequest $request)
    {
        $this->roleService->create($request->validated());

        return redirect()
            ->route('security.roles.index')
            ->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(int $role)
    {
        $role = $this->roleService->findById($role);
        $permissionGroups = $this->groupedPermissions();
        $assignedPermissionIds = $role->permissions->pluck('id')->all();

        return view('security::roles.edit', compact('role', 'permissionGroups', 'assignedPermissionIds'));
    }

    public function update(UpdateRoleRequest $request, int $role)
    {
        $this->roleService->update($role, $request->validated());

        return redirect()
            ->route('security.roles.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(int $role)
    {
        try {
            $this->roleService->delete($role);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('security.roles.index')
            ->with('success', 'Role berhasil dihapus.');
    }

    protected function groupedPermissions()
    {
        return Permission::orderBy('code')
            ->get()
            ->groupBy(fn($permission) => explode('.', $permission->code)[0]);
    }
}
