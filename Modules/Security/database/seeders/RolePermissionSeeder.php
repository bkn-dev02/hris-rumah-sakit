<?php

namespace Modules\Security\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Security\Models\Permission;
use Modules\Security\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin dapat semua permission
        $superAdmin = Role::where('code', 'super-admin')->first();
        $superAdmin?->permissions()->sync(Permission::pluck('id'));

        // Admin cuma dapat izin "view" untuk sementara
        $admin = Role::where('code', 'admin')->first();
        $viewPermissionIds = Permission::where('code', 'like', '%.view')->pluck('id');
        $admin?->permissions()->sync($viewPermissionIds);
    }
}
