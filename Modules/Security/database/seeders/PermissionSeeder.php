<?php

namespace Modules\Security\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Security\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['module' => 'Security', 'name' => 'Lihat Permission', 'code' => 'permissions.view'],
            ['module' => 'Security', 'name' => 'Tambah Permission', 'code' => 'permissions.create'],
            ['module' => 'Security', 'name' => 'Edit Permission', 'code' => 'permissions.update'],
            ['module' => 'Security', 'name' => 'Hapus Permission', 'code' => 'permissions.delete'],

            // Security - User
            ['module' => 'Security', 'name' => 'Lihat User', 'code' => 'users.view'],
            ['module' => 'Security', 'name' => 'Tambah User', 'code' => 'users.create'],
            ['module' => 'Security', 'name' => 'Edit User', 'code' => 'users.update'],
            ['module' => 'Security', 'name' => 'Hapus User', 'code' => 'users.delete'],
            ['module' => 'Security', 'name' => 'Lihat Riwayat Login', 'code' => 'login-histories.view'],

            // Security - Role
            ['module' => 'Security', 'name' => 'Lihat Role', 'code' => 'roles.view'],
            ['module' => 'Security', 'name' => 'Tambah Role', 'code' => 'roles.create'],
            ['module' => 'Security', 'name' => 'Edit Role', 'code' => 'roles.update'],
            ['module' => 'Security', 'name' => 'Hapus Role', 'code' => 'roles.delete'],

            // Master - Department
            ['module' => 'Master', 'name' => 'Lihat Department', 'code' => 'departments.view'],
            ['module' => 'Master', 'name' => 'Tambah Department', 'code' => 'departments.create'],
            ['module' => 'Master', 'name' => 'Edit Department', 'code' => 'departments.update'],
            ['module' => 'Master', 'name' => 'Hapus Department', 'code' => 'departments.delete'],

            // Master - Position
            ['module' => 'Master', 'name' => 'Lihat Position', 'code' => 'positions.view'],
            ['module' => 'Master', 'name' => 'Tambah Position', 'code' => 'positions.create'],
            ['module' => 'Master', 'name' => 'Edit Position', 'code' => 'positions.update'],
            ['module' => 'Master', 'name' => 'Hapus Position', 'code' => 'positions.delete'],

            // Master - Employment Status
            ['module' => 'Master', 'name' => 'Lihat Status Kepegawaian', 'code' => 'employment-statuses.view'],
            ['module' => 'Master', 'name' => 'Tambah Status Kepegawaian', 'code' => 'employment-statuses.create'],
            ['module' => 'Master', 'name' => 'Edit Status Kepegawaian', 'code' => 'employment-statuses.update'],
            ['module' => 'Master', 'name' => 'Hapus Status Kepegawaian', 'code' => 'employment-statuses.delete'],

            // Master - Shift (tanpa update, sesuai pola versioning)
            ['module' => 'Master', 'name' => 'Lihat Shift', 'code' => 'shifts.view'],
            ['module' => 'Master', 'name' => 'Tambah/Ubah Shift', 'code' => 'shifts.create'],
            ['module' => 'Master', 'name' => 'Hapus Shift', 'code' => 'shifts.delete'],

            // Master - Employee
            ['module' => 'Master', 'name' => 'Lihat Employee', 'code' => 'employees.view'],
            ['module' => 'Master', 'name' => 'Tambah Employee', 'code' => 'employees.create'],
            ['module' => 'Master', 'name' => 'Edit Employee', 'code' => 'employees.update'],
            ['module' => 'Master', 'name' => 'Hapus Employee', 'code' => 'employees.delete'],

            // Attendance - Rekap & Koreksi
            ['module' => 'Attendance', 'name' => 'Lihat Rekap Absensi', 'code' => 'attendances.view'],
            ['module' => 'Attendance', 'name' => 'Koreksi Status Absensi', 'code' => 'attendances.correct'],

            // Attendance - Pengajuan Exception
            ['module' => 'Attendance', 'name' => 'Lihat Pengajuan Absensi', 'code' => 'attendance-exceptions.view'],
            ['module' => 'Attendance', 'name' => 'Approve/Reject Pengajuan Absensi', 'code' => 'attendance-exceptions.approve'],

            // Attendance - Lokasi Absensi
            ['module' => 'Attendance', 'name' => 'Lihat Lokasi Absensi', 'code' => 'attendance-locations.view'],
            ['module' => 'Attendance', 'name' => 'Tambah Lokasi Absensi', 'code' => 'attendance-locations.create'],
            ['module' => 'Attendance', 'name' => 'Edit Lokasi Absensi', 'code' => 'attendance-locations.update'],
            ['module' => 'Attendance', 'name' => 'Hapus Lokasi Absensi', 'code' => 'attendance-locations.delete'],

            // Attendance - Status Kehadiran
            ['module' => 'Attendance', 'name' => 'Lihat Status Kehadiran', 'code' => 'attendance-statuses.view'],
            ['module' => 'Attendance', 'name' => 'Tambah Status Kehadiran', 'code' => 'attendance-statuses.create'],
            ['module' => 'Attendance', 'name' => 'Edit Status Kehadiran', 'code' => 'attendance-statuses.update'],
            ['module' => 'Attendance', 'name' => 'Hapus Status Kehadiran', 'code' => 'attendance-statuses.delete'],
            // Attendance - Presensi Darurat
            ['module' => 'Attendance', 'name' => 'Approve Presensi Darurat', 'code' => 'emergency-attendance.approve'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['code' => $permission['code']],
                $permission
            );
        }
    }
}
