<?php

namespace Modules\Leave\Services;

use Modules\Master\Models\Employee;
use RuntimeException;

class ApprovalChainBuilder
{
    public function build(Employee $submitter): array
    {
        $chain = [];
        $current = $submitter->directSupervisor();
        $guard = 0;

        while ($current) {
            $chain[] = ['employee' => $current, 'type' => 'supervisor'];

            $current = $current->directSupervisor();

            if (++$guard > 20) {
                throw new RuntimeException('Struktur hierarki jabatan terlalu dalam atau ada siklus.');
            }
        }

        $hrd = $this->resolveHrd();
        $chain[] = ['employee' => $hrd, 'type' => 'hrd'];

        $director = $this->resolveDirector();
        if ($director) {
            $alreadyInChain = collect($chain)->contains(fn ($step) => $step['employee']->id === $director->id);

            if (! $alreadyInChain) {
                $chain[] = ['employee' => $director, 'type' => 'director'];
            }
        }

        return array_values(array_map(
            fn(array $step, int $i) => $step + ['sequence' => $i + 1],
            $chain,
            array_keys($chain)
        ));
    }

    protected function resolveHrd(): Employee
    {
        $hrd = Employee::query()
            ->whereHas('user.roles', fn($q) => $q->where('code', 'hrd'))
            ->get()
            ->sortByDesc(fn(Employee $employee) => $employee->currentPosition()?->level ?? -1)
            ->first();

        if (! $hrd) {
            throw new RuntimeException('Approver HRD belum diatur.');
        }

        return $hrd;
    }

    protected function resolveDirector(): ?Employee
    {
        return Employee::query()
            ->whereHas('user.roles', fn($q) => $q->where('code', 'direktur'))
            ->first();
    }
}