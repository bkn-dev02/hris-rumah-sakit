<?php

namespace Modules\Master\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Master\Contracts\Repositories\EmployeeRepositoryInterface;
use Modules\Master\Contracts\Services\EmployeeServiceInterface;
use Modules\Master\DTOs\EmployeeData;
use Modules\Master\Models\Employee;

class EmployeeService implements EmployeeServiceInterface
{
    public function __construct(
        protected EmployeeRepositoryInterface $employeeRepository
    ) {}

    public function getAll(): Collection
    {
        return $this->employeeRepository->all();
    }

    public function getEmployeeStatusCounts(): array
    {
        return $this->employeeRepository->getActiveInactiveCounts();
    }

    public function paginate(int $perPage = 10, bool $trashed = false, ?array $departmentIds = null): LengthAwarePaginator
    {
        return $this->employeeRepository->paginate($perPage, $trashed, $departmentIds);
    }

    public function findBySlug(string $slug, bool $withTrashed = false): Employee
    {
        $employee = $this->employeeRepository->findBySlug($slug, $withTrashed);

        if (!$employee) {
            throw new ModelNotFoundException('Employee tidak ditemukan.');
        }

        return $employee;
    }

    public function create(EmployeeData $data, ?string $photo = null): Employee
    {
        return DB::transaction(function () use ($data, $photo) {

            $payload = $data->toArray();
            $payload['slug'] = $this->generateUniqueSlug($data->name);
            $payload['photo'] = $photo;

            return $this->employeeRepository->create($payload);
        });
    }

    public function update(string $slug, EmployeeData $data, ?string $photo = null): Employee
    {
        return DB::transaction(function () use ($slug, $data, $photo) {

            $employee = $this->findBySlug($slug);

            $payload = $data->toArray();

            if ($photo !== null) {
                $payload['photo'] = $photo;
            }

            $this->employeeRepository->update($employee, $payload);

            return $employee->fresh();
        });
    }

    public function delete(string $slug): bool
    {
        return DB::transaction(function () use ($slug) {

            $employee = $this->findBySlug($slug);

            return $this->employeeRepository->delete($employee);
        });
    }

    public function restore(string $slug): bool
    {
        return DB::transaction(function () use ($slug) {

            return $this->employeeRepository->restore($slug);
        });
    }

    public function forceDelete(string $slug): bool
    {
        return DB::transaction(function () use ($slug) {

            $employee = $this->findBySlug($slug, withTrashed: true);

            return $this->employeeRepository->forceDelete($employee);
        });
    }

    protected function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while ($this->employeeRepository->findBySlug($slug, withTrashed: true)) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }

    public function getDepartmentDistribution(): array
    {
        return $this->employeeRepository->getDepartmentDistribution();
    }
}
