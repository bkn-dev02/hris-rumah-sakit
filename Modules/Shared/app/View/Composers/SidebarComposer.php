<?php

namespace Modules\Shared\View\Composers;

use Illuminate\View\View;

class SidebarComposer
{
    public function compose(View $view): void
    {
        $menus = [
            [
                'label' => 'Dashboard',
                'icon'  => 'fa-solid fa-gauge',
                'route' => 'dashboard.index',
                'active' => ['dashboard.*'],
            ],
            [
                'label' => 'Data Masters',
                'icon'  => 'fa-solid fa-users',
                'route' => 'master.index',
                'active' => ['master.*'],
                'children' => [
                    [
                        'label' => 'Manajemen Pegawai',
                        'icon'  => 'fa-solid fa-user',
                        'route' => 'master.employees.index',
                        'active' => ['master.employees.*'],
                    ],
                    [
                        'label' => 'Manajemen Department',
                        'icon'  => 'fa-solid fa-building',
                        'route' => 'master.departments.index',
                        'active' => ['master.departments.*'],
                    ],
                    [
                        'label' => 'Manajemen Position',
                        'icon'  => 'fa-solid fa-briefcase',
                        'route' => 'master.positions.index',
                        'active' => ['master.positions.*'],
                    ],
                    [
                        'label' => 'Manejemen Shift',
                        'icon'  => 'fa-solid fa-clock',
                        'route' => 'master.shifts.index',
                        'active' => ['master.shifts.*'],
                    ],
                    [
                        'label' => 'Status Kepegawaian',
                        'icon'  => 'fa-solid fa-user-tag',
                        'route' => 'master.employment-statuses.index',
                        'active' => ['master.employment-statuses.*'],
                        'permission' => 'employment-statuses.view',
                    ],
                ]
            ],
            [
                'label' => 'Attendance',
                'icon'  => 'fa-solid fa-fingerprint',
                'route' => 'attendance.index',
                'active' => ['attendance.index', 'attendance.attendances.*'],
                'children' => [
                    [
                        'label' => 'Rekap Absensi',
                        'icon'  => 'fa-solid fa-calendar-check',
                        'route' => 'attendance.attendances.index',
                        'active' => ['attendance.attendances.*'],
                        'permission' => 'attendances.view',
                    ],
                    [
                        'label' => 'Presensi Darurat',
                        'icon'  => 'fa-solid fa-triangle-exclamation',
                        'route' => 'attendance.emergency.index',
                        'active' => ['attendance.emergency.*'],
                        'permission' => 'emergency-attendance.approve',
                    ],
                    [
                        'label' => 'Pengajuan',
                        'icon'  => 'fa-solid fa-file-signature',
                        'route' => 'attendance.exception-requests.index',
                        'active' => ['attendance.exception-requests.*'],
                        'permission' => 'attendance-exceptions.view',
                    ],
                    [
                        'label' => 'Lokasi Absensi',
                        'icon'  => 'fa-solid fa-location-dot',
                        'route' => 'attendance.locations.index',
                        'active' => ['attendance.locations.*'],
                        'permission' => 'attendance-locations.view',
                    ],
                    [
                        'label' => 'Status Kehadiran',
                        'icon'  => 'fa-solid fa-list-check',
                        'route' => 'attendance.statuses.index',
                        'active' => ['attendance.statuses.*'],
                        'permission' => 'attendance-statuses.view',
                    ],
                ],
            ],
            [
                'label' => 'Jadwal & SP',
                'icon'  => 'fa-solid fa-calendar-week',
                'route' => 'schedule.index',
                'active' => ['schedule.index', 'schedule.sp-candidates.*'],
                'children' => [
                    [
                        'label' => 'Jadwal Mingguan',
                        'icon'  => 'fa-solid fa-table-cells',
                        'route' => 'schedule.index',
                        'active' => ['schedule.index'],
                        'permission' => 'schedule.view',
                    ],
                    [
                        'label' => 'SP Candidate',
                        'icon'  => 'fa-solid fa-triangle-exclamation',
                        'route' => 'schedule.sp-candidates.index',
                        'active' => ['schedule.sp-candidates.*'],
                        'permission' => 'sp-candidates.view',
                    ],
                    [
                        'label' => 'Review Bulanan',
                        'icon'  => 'fa-solid fa-calendar-days',
                        'route' => 'schedule.monthly-grid.index',
                        'active' => ['schedule.monthly-grid.*'],
                        'permission' => 'schedule.view',
                    ],
                ],
            ],
            [
                'label' => 'Manajemen Cuti',
                'icon'  => 'fa-solid fa-calendar-days',
                'route' => 'leave.index',
                'active' => ['leave.index'],
                'children' => [
                    [
                        'label' => 'Pengajuan Cuti',
                        'icon'  => 'fa-solid fa-file-signature',
                        'route' => 'leave.requests.index',
                        'active' => ['leave.requests.index'],
                        'permission' => 'leave-requests.view',
                    ],
                    [
                        'label' => 'Jenis Cuti',
                        'icon'  => 'fa-solid fa-list-ul',
                        'route' => 'leave.leave-types.index',
                        'active' => ['leave.leave-types.*'],
                        'permission' => 'leave-types.manage',
                    ],
                ],
            ],
            [
                'label' => 'Security & Account',
                'icon'  => 'fa-solid fa-shield-halved',
                'route' => 'security.index',
                'active' => ['security.index'],
                'children' => [
                    [
                        'label' => 'User Management',
                        'icon'  => 'fa-solid fa-user',
                        'route' => 'security.users.index',
                        'active' => ['security.users.*'],
                    ],
                    [
                        'label' => 'Role Management',
                        'icon'  => 'fa-solid fa-user-tag',
                        'route' => 'security.roles.index',
                        'active' => ['security.roles.*'],
                    ],
                    [
                        'label' => 'Permission',
                        'icon'  => 'fa-solid fa-key',
                        'route' => 'security.permissions.index',
                        'active' => ['security.permissions.*'],
                        'permission' => 'permissions.view',
                    ],
                    [
                        'label' => 'Riwayat Login',
                        'icon'  => 'fa-solid fa-clock-rotate-left',
                        'route' => 'security.login-histories.index',
                        'active' => ['security.login-histories.*'],
                        'permission' => 'login-histories.view',
                    ],
                ],
            ],
        ];

        $openMenu = collect($menus)->first(function ($menu) {
            if (!isset($menu['children'])) {
                return false;
            }

            $patterns = collect($menu['children'])
                ->flatMap(fn($child) => $child['active'])
                ->all();

            return request()->routeIs(...$patterns);
        })['label'] ?? '';

        $view->with(compact('menus', 'openMenu'));
    }
}
