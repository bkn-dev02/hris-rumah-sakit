<?php

namespace Modules\Master\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Master\Contracts\Services\DepartmentServiceInterface;
use Modules\Master\Contracts\Services\EmployeeServiceInterface;
use Modules\Master\Contracts\Services\EmploymentStatusServiceInterface;
use Modules\Master\Contracts\Services\PositionServiceInterface;
use Modules\Master\Contracts\Services\ShiftServiceInterface;

class MasterController extends Controller
{
    public function __construct(
        protected EmployeeServiceInterface $employeeService,
        protected DepartmentServiceInterface $departmentService,
        protected PositionServiceInterface $positionService,
        protected ShiftServiceInterface $shiftService,
        protected EmploymentStatusServiceInterface $employmentStatusService,
    ) {}

    public function index()
    {
        $cards = [
            [
                'label'      => 'Employee',
                'icon'       => 'fa-solid fa-id-card',
                'count'      => $this->employeeService->getAll()->count(),
                'route'      => 'master.employees.index',
                'permission' => 'employees.view',
            ],
            [
                'label'      => 'Department',
                'icon'       => 'fa-solid fa-sitemap',
                'count'      => $this->departmentService->getAll()->count(),
                'route'      => 'master.departments.index',
                'permission' => 'departments.view',
            ],
            [
                'label'      => 'Position',
                'icon'       => 'fa-solid fa-briefcase',
                'count'      => $this->positionService->getAll()->count(),
                'route'      => 'master.positions.index',
                'permission' => 'positions.view',
            ],
            [
                'label'      => 'Shift',
                'icon'       => 'fa-solid fa-business-time',
                'count'      => $this->shiftService->activeList()->count(),
                'route'      => 'master.shifts.index',
                'permission' => 'shifts.view',
            ],
            [
                'label'      => 'Status Kepegawaian',
                'icon'       => 'fa-solid fa-user-tag',
                'count'      => $this->employmentStatusService->getAll()->count(),
                'route'      => 'master.employment-statuses.index',
                'permission' => 'employment-statuses.view',
            ],
        ];

        return view('master::index', compact('cards'));
    }
}
