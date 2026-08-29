@extends('shared::layouts.app')

@section('title', 'Review Jadwal Bulanan')

@section('content')
<div class="max-w-full px-6 py-8">

    <div class="bg-gradient-to-r from-sky-950 to-sky-800 rounded-t-2xl px-6 py-4 flex items-center justify-between flex-wrap gap-3 shadow-md">
        <div class="flex items-center gap-3">
            <i class="fas fa-calendar-days text-sky-300"></i>
            <h1 class="text-white font-semibold text-lg">Review Jadwal Bulanan</h1>
        </div>

        <form method="GET" class="flex items-center gap-3 flex-wrap">
            @if ($showFilter)
            <select name="department_id" onchange="this.form.submit()"
                class="rounded-lg border-0 text-sm py-2 px-3 shadow-sm focus:ring-2 focus:ring-sky-400">
                @if ($departmentsForFilter->count() > 1 || !$departmentId)
                <option value="">Pilih Departemen</option>
                @endif
                @foreach ($departmentsForFilter as $department)
                <option value="{{ $department->id }}" {{ $departmentId == $department->id ? 'selected' : '' }}>
                    {{ $department->name }}
                </option>
                @endforeach
            </select>
            @else
            <span class="text-sky-200 text-sm font-medium px-1">
                {{ $departmentsForFilter->first()->name ?? '' }}
            </span>
            <input type="hidden" name="department_id" value="{{ $departmentId }}">
            @endif

            <div class="flex items-center gap-2">
                <a href="{{ route('schedule.monthly-grid.index', array_merge(request()->query(), ['year' => $monthDate->copy()->subMonth()->year, 'month' => $monthDate->copy()->subMonth()->month])) }}"
                    class="text-white/80 hover:text-white transition">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <span class="text-white text-sm px-2 min-w-[120px] text-center">
                    {{ $monthDate->translatedFormat('F Y') }}
                </span>
                <a href="{{ route('schedule.monthly-grid.index', array_merge(request()->query(), ['year' => $monthDate->copy()->addMonth()->year, 'month' => $monthDate->copy()->addMonth()->month])) }}"
                    class="text-white/80 hover:text-white transition">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-b-2xl shadow-md overflow-x-auto">
        @if (!$departmentId)
        <div class="p-10 text-center text-slate-400">
            <i class="fas fa-building text-3xl mb-3"></i>
            <p>Pilih departemen untuk melihat review jadwal.</p>
        </div>
        @elseif ($employees->isEmpty())
        <div class="p-10 text-center text-slate-400">
            <i class="fas fa-user-slash text-3xl mb-3"></i>
            <p>Tidak ada pegawai aktif di departemen ini.</p>
        </div>
        @else
        <table class="text-xs">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="text-left px-4 py-3 font-medium text-slate-500 sticky left-0 bg-white z-10 min-w-[160px]">Pegawai</th>
                    @foreach ($dates as $date)
                    <th class="px-1 py-3 font-medium text-slate-400 text-center w-8 {{ $date->isWeekend() ? 'bg-slate-50' : '' }}">
                        {{ $date->format('d') }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                <tr class="border-b border-slate-50 hover:bg-sky-50/40 transition">
                    <td class="px-4 py-2 font-medium text-slate-700 whitespace-nowrap sticky left-0 bg-white">
                        {{ $employee->name }}
                    </td>
                    @foreach ($dates as $date)
                    @php
                    $entry = $scheduleMap[$employee->id][$date->toDateString()] ?? null;
                    $label = $entry
                    ? ($entry['type'] === 'libur' ? 'L' : $entry['shift_label'])
                    : '-';
                    @endphp
                    <td class="px-1 py-2 text-center {{ $date->isWeekend() ? 'bg-slate-50' : '' }} {{ $label === '-' ? 'text-slate-300' : 'text-slate-700 font-medium' }}">
                        {{ $label }}
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection