<?php

namespace Modules\Master\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Modules\Master\Http\Requests\StoreDepartmentRequest;
use Modules\Master\Http\Requests\UpdateDepartmentRequest;
use Modules\Master\Contracts\Services\DepartmentServiceInterface;

class DepartmentController extends Controller
{
    public function __construct(
        protected DepartmentServiceInterface $departmentService
    ) {}

    public function index()
    {
        $departments = $this->departmentService->paginate(10);

        return view('master::departments.index', compact('departments'));
    }

    public function create()
    {
        $departments = $this->departmentService->getAll();

        return view('master::departments.create', compact('departments'));
    }

    public function store(StoreDepartmentRequest $request)
    {
        $this->departmentService->create($request->validated());

        return redirect()
            ->route('master.departments.index')
            ->with('success', 'Department berhasil ditambahkan.');
    }

    public function edit(int $department)
    {
        $department = $this->departmentService->findById($department);
        $departments = $this->departmentService->getAll()->where('id', '!=', $department->id);

        return view('master::departments.edit', compact('department', 'departments'));
    }

    public function update(UpdateDepartmentRequest $request, int $department)
    {
        $this->departmentService->update($department, $request->validated());

        return redirect()
            ->route('master.departments.index')
            ->with('success', 'Department berhasil diperbarui.');
    }

    public function destroy(int $department)
    {
        try {
            $this->departmentService->delete($department);
        } catch (QueryException $e) {
            return back()->with('error', 'Department tidak dapat dihapus karena masih digunakan.');
        }

        return redirect()
            ->route('master.departments.index')
            ->with('success', 'Department berhasil dihapus.');
    }

    public function tree()
    {
        $departments = $this->departmentService->tree();

        return view('master::departments.tree', compact('departments'));
    }
}
