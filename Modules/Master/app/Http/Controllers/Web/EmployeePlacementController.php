<?php

namespace Modules\Master\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Master\Http\Requests\StoreEmployeePlacementRequest;
use Modules\Master\Contracts\Services\DepartmentServiceInterface;
use Modules\Master\Contracts\Services\EmployeePlacementServiceInterface;
use Modules\Master\Contracts\Services\EmployeeServiceInterface;
use Modules\Master\Contracts\Services\PositionServiceInterface;
use Modules\Master\DTOs\EmployeePlacementData;

class EmployeePlacementController extends Controller
{
    public function __construct(
        protected EmployeePlacementServiceInterface $placementService,
        protected EmployeeServiceInterface $employeeService,
        protected DepartmentServiceInterface $departmentService,
        protected PositionServiceInterface $positionService
    ) {}

    public function index(string $employee)
    {
        $employee = $this->employeeService->findBySlug($employee);
        $history = $this->placementService->history($employee->id);

        return view('master::employees.placements.index', compact('employee', 'history'));
    }

    public function create(string $employee)
    {
        $employee = $this->employeeService->findBySlug($employee);
        $currentPlacement = $this->placementService->current($employee->id);
        $departments = $this->departmentService->getAll();
        $positions = $this->positionService->getAll();

        return view('master::employees.placements.create', compact(
            'employee',
            'currentPlacement',
            'departments',
            'positions'
        ));
    }

    public function store(StoreEmployeePlacementRequest $request, string $employee)
    {
        $employee = $this->employeeService->findBySlug($employee);

        $data = EmployeePlacementData::fromArray([
            ...$request->validated(),
            'employee_id' => $employee->id,
            'created_by'  => Auth::id(),
        ]);

        $this->placementService->createPlacement($data);

        return redirect()
            ->route('master.employees.placements.index', $employee->slug)
            ->with('success', 'Penempatan berhasil disimpan.');
    }
}
