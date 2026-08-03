<?php

namespace Modules\Security\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Security\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'username' => 'admin',
            ],
            [
                'slug' => 'usr_' . Str::ulid(),
                'employee_id' => null,
                'username' => 'admin',
                'email' => 'admin@hris.com',
                'password' => bcrypt('password'),
                'is_active' => true,
                'last_login_at' => null,
            ]
        );
    }
}
