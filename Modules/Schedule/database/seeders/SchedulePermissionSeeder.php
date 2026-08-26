<?php

namespace Modules\Schedule\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Security\Models\Permission;
use Modules\Security\Models\Role;

class SchedulePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'module' => 'Schedule',
                'name' => 'Kelola Jadwal',
                'code' => 'schedule.manage',
                'description' => 'Membuat dan mengubah jadwal kerja/libur pegawai per hari',
            ],
            [
                'module' => 'Schedule',
                'name' => 'Lihat Jadwal',
                'code' => 'schedule.view',
                'description' => 'Melihat jadwal dan distribusi pegawai per departemen/shift',
            ],
            [
                'module' => 'Schedule',
                'name' => 'Lihat SP Candidate',
                'code' => 'sp-candidates.view',
                'description' => 'Melihat daftar SP Candidate dan riwayat SP pegawai',
            ],
            [
                'module' => 'Schedule',
                'name' => 'Konfirmasi Manual SP',
                'code' => 'sp-candidates.confirm',
                'description' => 'Membuat Konfirmasi Manual untuk mencegah/membatalkan SP Candidate',
            ],
            [
                'module' => 'Schedule',
                'name' => 'Terbitkan Surat SP',
                'code' => 'sp-letters.issue',
                'description' => 'Memutuskan dan menerbitkan Surat Peringatan resmi',
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

            $createdIds[$permission['code']] = $record->id;
        }

        $superAdmin = Role::where('code', 'super-admin')->first();
        $superAdmin?->permissions()->syncWithoutDetaching(array_values($createdIds));

        // Kepala Ruangan (kepala-unit): kelola jadwal, lihat & konfirmasi manual SP di departemennya
        Role::where('code', 'kepala-unit')->first()?->permissions()->syncWithoutDetaching([
            $createdIds['schedule.manage'],
            $createdIds['schedule.view'],
            $createdIds['sp-candidates.view'],
            $createdIds['sp-candidates.confirm'],
        ]);

        // HRD: semua akses, termasuk terbitkan SP
        Role::where('code', 'hrd')->first()?->permissions()->syncWithoutDetaching([
            $createdIds['schedule.view'],
            $createdIds['sp-candidates.view'],
            $createdIds['sp-candidates.confirm'],
            $createdIds['sp-letters.issue'],
        ]);

        // Direktur: view-only
        Role::where('code', 'direktur')->first()?->permissions()->syncWithoutDetaching([
            $createdIds['schedule.view'],
            $createdIds['sp-candidates.view'],
        ]);
    }
}
