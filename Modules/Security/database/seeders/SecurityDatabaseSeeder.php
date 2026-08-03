<?php

namespace Modules\Security\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Security\Models\Role;
use Modules\Security\Models\User;

class SecurityDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            RolePermissionSeeder::class,
        ]);

        $admin = User::factory()->create([
            'username' => 'admin',
            'email'    => 'admin@hris.com',
            'password' => bcrypt('password'),
        ]);

        $superAdminRole = Role::where('code', 'super-admin')->first();
        $admin->roles()->attach($superAdminRole->id);
    }
}
