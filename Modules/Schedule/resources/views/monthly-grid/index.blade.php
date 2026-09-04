@extends('shared::layouts.app')

@section('title', 'Review Jadwal Bulanan')

@section('content')
<div class="max-w-full px-6 py-8">

    <div class="bg-gradient-to-r from-[#173f34] to-[#2a684f] rounded-t-2xl px-6 py-4 flex items-center justify-between flex-wrap gap-3 shadow-md">
        <div class="flex items-center gap-3">
            <i class="fas fa-calendar-days text-[#dfeee1]"></i>
            <h1 class="text-white font-semibold text-lg">Review Jadwal Bulanan</h1>
        </div>

        <form method="GET" class="flex items-center gap-3 flex-wrap">
            @if ($showFilter)
            <select name="department_id" onchange="this.form.submit()"
                class="rounded-lg border-0 text-sm py-2 px-3 shadow-sm focus:ring-2 focus:ring-[#dfeee1]">
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
            <span class="text-[#dfeee1] text-sm font-medium px-1">
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

    @if ($personalSchedule)
    <div class="mt-4 rounded-2xl border border-[#dfeee1] bg-white p-5 shadow-xl">
        <div class="mb-4 flex items-center gap-2">
            <i class="fas fa-user-clock text-[#2a684f]"></i>
            <div>
                <h2 class="font-semibold text-[#173f34]">Jadwal Saya</h2>
                <p class="mt-1 text-xs text-slate-500">Jadwal pribadi kepala unit pada bulan ini</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-5 xl:grid-cols-7">
            @foreach ($personalSchedule as $entry)
            <div class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 {{ \Carbon\Carbon::parse($entry['date'])->isWeekend() ? 'bg-slate-50' : 'bg-white' }}">
                <div>
                    <p class="text-xs font-semibold text-slate-700">{{ \Carbon\Carbon::parse($entry['date'])->translatedFormat('D, d M') }}</p>
                    @if ($entry['is_libur'])
                    <p class="mt-0.5 text-xs text-slate-500">Libur</p>
                    @elseif ($entry['shift'])
                    <p class="mt-0.5 text-xs text-[#567564]">{{ $entry['shift']['name'] }} · {{ $entry['shift']['start_time'] }}-{{ $entry['shift']['end_time'] }}</p>
                    @else
                    <p class="mt-0.5 text-xs text-slate-400">Belum diatur</p>
                    @endif
                </div>
                <i class="fas {{ $entry['is_libur'] ? 'fa-mug-hot text-slate-400' : ($entry['shift'] ? 'fa-clock text-[#2a684f]' : 'fa-minus text-slate-300') }} text-xs"></i>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-xl overflow-x-auto mt-4">
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
        @php
        $gridTemplateColumns = '180px repeat(' . $dates->count() . ', 40px)';
        @endphp
        <div class="w-max min-w-full text-xs">
            <div class="grid border-b border-slate-100 bg-white" style="grid-template-columns: {{ $gridTemplateColumns }}">
                <div class="sticky left-0 z-10 w-[180px] bg-white px-4 py-3 text-left font-medium text-slate-500">Pegawai</div>
                @foreach ($dates as $date)
                <div class="px-1 py-3 text-center font-medium text-slate-400 {{ $date->isWeekend() ? 'bg-slate-50' : '' }}">
                    {{ $date->format('d') }}
                </div>
                @endforeach
            </div>

            @foreach ($employees as $employee)
            <div class="grid border-b border-slate-50 transition hover:bg-[#f8fbf8]/40" style="grid-template-columns: {{ $gridTemplateColumns }}">
                <div class="sticky left-0 z-10 w-[180px] min-w-0 bg-white px-4 py-2 font-medium text-slate-700">
                    <span class="block truncate" title="{{ $employee->name }}">
                        {{ $employee->name }}
                    </span>
                </div>
                @foreach ($dates as $date)
                @php
                $entry = $scheduleMap[$employee->id][$date->toDateString()] ?? null;
                $label = $entry
                ? ($entry['type'] === 'libur' ? 'L' : $entry['shift_label'])
                : '-';
                @endphp
                <div class="px-1 py-2 text-center {{ $date->isWeekend() ? 'bg-slate-50' : '' }} {{ $label === '-' ? 'text-slate-300' : 'font-medium text-slate-700' }}">
                    {{ $label }}
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection