<?php

namespace Modules\Master\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Modules\Master\Http\Requests\StoreEmploymentStatusRequest;
use Modules\Master\Http\Requests\UpdateEmploymentStatusRequest;
use Modules\Master\Contracts\Services\EmploymentStatusServiceInterface;

class EmploymentStatusController extends Controller
{
    public function __construct(
        protected EmploymentStatusServiceInterface $employmentStatusService
    ) {}

    public function index()
    {
        $employmentStatuses = $this->employmentStatusService->paginate(10);

        return view('master::employment-statuses.index', compact('employmentStatuses'));
    }

    public function create()
    {
        return view('master::employment-statuses.create');
    }

    public function store(StoreEmploymentStatusRequest $request)
    {
        $this->employmentStatusService->create($request->validated());

        return redirect()
            ->route('master.employment-statuses.index')
            ->with('success', 'Status kepegawaian berhasil ditambahkan.');
    }

    public function edit(int $employmentStatus)
    {
        $employmentStatus = $this->employmentStatusService->findById($employmentStatus);

        return view('master::employment-statuses.edit', compact('employmentStatus'));
    }

    public function update(UpdateEmploymentStatusRequest $request, int $employmentStatus)
    {
        $this->employmentStatusService->update($employmentStatus, $request->validated());

        return redirect()
            ->route('master.employment-statuses.index')
            ->with('success', 'Status kepegawaian berhasil diperbarui.');
    }

    public function destroy(int $employmentStatus)
    {
        try {
            $this->employmentStatusService->delete($employmentStatus);
        } catch (QueryException $e) {
            return back()->with('error', 'Status kepegawaian tidak dapat dihapus karena masih digunakan.');
        }

        return redirect()
            ->route('master.employment-statuses.index')
            ->with('success', 'Status kepegawaian berhasil dihapus.');
    }
}
