<?php

namespace Modules\Leave\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Security\Models\Permission;
use Modules\Security\Models\Role;

class LeavePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'module' => 'Leave',
                'name' => 'Kelola Jenis Cuti',
                'code' => 'leave-types.manage',
                'description' => 'Membuat, mengubah, dan menghapus jenis cuti',
            ],
            [
                'module' => 'Leave',
                'name' => 'Kelola Kuota Cuti',
                'code' => 'leave-quotas.manage',
                'description' => 'Menetapkan jatah kuota cuti per karyawan',
            ],
            [
                'module' => 'Leave',
                'name' => 'Lihat Pengajuan Cuti',
                'code' => 'leave-requests.view',
                'description' => 'Melihat daftar pengajuan cuti',
            ],
            [
                'module' => 'Leave',
                'name' => 'Approve Cuti (Atasan)',
                'code' => 'leave-requests.approve-supervisor',
                'description' => 'Menyetujui atau menolak pengajuan cuti sebagai atasan',
            ],
            [
                'module' => 'Leave',
                'name' => 'Approve Cuti (HRD)',
                'code' => 'leave-requests.approve-hr',
                'description' => 'Menyetujui atau menolak pengajuan cuti sebagai HRD (final)',
            ],
        ];

        $createdIds = [];

        foreach ($permissions as $permission) {
            $record = Permission::query()->updateOrCreate(
                ['code' => $permission['code']],
                [
                    'module' => $permission['module'],
                    'name' => $permission['name'],
                    'description' => $permission['description'],
                    'is_active' => true,
                ]
            );

            $createdIds[] = $record->id;
        }

        $superAdmin = Role::where('code', 'super-admin')->first();
        $superAdmin?->permissions()->syncWithoutDetaching($createdIds);
    }
}
