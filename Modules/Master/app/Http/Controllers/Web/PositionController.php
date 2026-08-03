<?php

namespace Modules\Master\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Modules\Master\Http\Requests\StorePositionRequest;
use Modules\Master\Http\Requests\UpdatePositionRequest;
use Modules\Master\Contracts\Services\PositionServiceInterface;

class PositionController extends Controller
{
    public function __construct(
        protected PositionServiceInterface $positionService
    ) {}

    public function index()
    {
        $positions = $this->positionService->paginate(10);

        return view('master::positions.index', compact('positions'));
    }

    public function create()
    {
        return view('master::positions.create');
    }

    public function store(StorePositionRequest $request)
    {
        $this->positionService->create($request->validated());

        return redirect()
            ->route('master.positions.index')
            ->with('success', 'Position berhasil ditambahkan.');
    }

    public function edit(int $position)
    {
        $position = $this->positionService->findById($position);

        return view('master::positions.edit', compact('position'));
    }

    public function update(UpdatePositionRequest $request, int $position)
    {
        $this->positionService->update($position, $request->validated());

        return redirect()
            ->route('master.positions.index')
            ->with('success', 'Position berhasil diperbarui.');
    }

    public function destroy(int $position)
    {
        try {
            $this->positionService->delete($position);
        } catch (QueryException $e) {
            return back()->with('error', 'Position tidak dapat dihapus karena masih digunakan.');
        }

        return redirect()
            ->route('master.positions.index')
            ->with('success', 'Position berhasil dihapus.');
    }
}
