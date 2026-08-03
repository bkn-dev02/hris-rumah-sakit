<?php

namespace Modules\Security\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Security\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name'        => 'Super Admin',
                'code'        => 'super-admin',
                'description' => 'Akses penuh ke seluruh sistem.',
                'is_system'   => true,
            ],
            [
                'name'        => 'Admin',
                'code'        => 'admin',
                'description' => 'Administrator operasional.',
                'is_system'   => false,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['code' => $role['code']],
                $role
            );
        }
    }
}
