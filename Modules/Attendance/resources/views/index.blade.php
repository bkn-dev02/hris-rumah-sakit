@extends('shared::layouts.app')

@section('title', 'Attendance Dashboard')

@section('content')
<div class="mx-auto max-w-7xl px-3 py-4 sm:px-6 sm:py-8">

    <div class="flex flex-col gap-3 rounded-xl bg-[#edf5ee] p-3 sm:flex-row sm:items-center sm:justify-between sm:p-4">
        <div class="flex items-start gap-2 sm:items-center">
            <i class="fa-solid fa-calendar-days text-xl text-[#173f34] sm:text-2xl"></i>
            <h1 class="text-base font-bold leading-snug text-[#173f34] sm:text-xl">
                Aktivitas Presensi Hari Ini, {{ now()->translatedFormat('d F Y') }}
            </h1>
        </div>
        <a href="{{ route('attendance.attendances.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#1f4d3d] px-3 py-2 text-xs font-medium text-white transition duration-200 hover:bg-[#173f34] sm:text-sm">
            <i class="fa-solid fa-list text-xs sm:text-sm"></i>
            Lihat Rekap Lengkap
        </a>
    </div>

    <div class="mt-4 grid grid-cols-1 gap-3 p-0 sm:grid-cols-2 sm:gap-4 sm:p-4 lg:grid-cols-4">

        {{-- Total Karyawan --}}
        <div class="group relative h-full transform cursor-pointer duration-300 transition hover:-translate-y-1">
            <div class="absolute -inset-0.5 rounded-2xl bg-gradient-to-r from-[#173f34] to-[#2a684f] opacity-30 blur transition duration-300 group-hover:opacity-100"></div>
            <div class="relative flex h-28 flex-col justify-center rounded-2xl bg-white p-4 shadow-lg transition-all duration-300 hover:shadow-xl sm:h-32 sm:p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-medium text-slate-500 sm:text-sm">Total Karyawan</p>
                        <h3 class="mt-1 text-2xl font-bold text-[#173f34] sm:text-3xl">{{ $summary['total'] }}</h3>
                    </div>
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#edf5ee] sm:h-10 sm:w-10">
                        <i class="fas fa-users text-base text-[#173f34] sm:text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hadir --}}
        <div class="group relative transform cursor-pointer duration-300 transition hover:-translate-y-1">
            <div class="absolute -inset-0.5 rounded-2xl bg-gradient-to-r from-emerald-600 to-emerald-400 opacity-30 blur transition duration-300 group-hover:opacity-100"></div>
            <div class="relative rounded-2xl bg-white p-4 shadow-lg transition-all duration-300 hover:shadow-xl sm:p-6">
                @php
                $presentPct = $summary['total'] > 0 ? round(($summary['present'] / $summary['total']) * 100) : 0;
                @endphp
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-medium text-slate-500 sm:text-sm">Hadir</p>
                        <h3 class="mt-1 text-2xl font-bold text-[#173f34] sm:text-3xl">{{ $summary['present'] }}</h3>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="rounded-full bg-emerald-50 px-2 py-1 text-[10px] font-medium text-emerald-700 sm:text-xs">{{ $presentPct }}%</span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 sm:h-10 sm:w-10">
                            <i class="fas fa-check-circle text-base text-emerald-600 sm:text-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1">
                    <div class="h-1.5 w-full rounded-full bg-slate-100">
                        <div class="h-1.5 rounded-full bg-gradient-to-r from-emerald-500 to-emerald-700" style="width: {{ $presentPct }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cuti/Izin --}}
        <div class="group relative transform cursor-pointer duration-300 transition hover:-translate-y-1">
            <div class="absolute -inset-0.5 rounded-2xl bg-gradient-to-r from-amber-600 to-amber-400 opacity-30 blur transition duration-300 group-hover:opacity-100"></div>
            <div class="relative rounded-2xl bg-white p-4 shadow-lg transition-all duration-300 hover:shadow-xl sm:p-6">
                @php
                $leavePct = $summary['total'] > 0 ? round(($summary['on_leave'] / $summary['total']) * 100) : 0;
                @endphp
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-medium text-slate-500 sm:text-sm">Cuti/Izin</p>
                        <h3 class="mt-1 text-2xl font-bold text-[#173f34] sm:text-3xl">{{ $summary['on_leave'] }}</h3>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="rounded-full bg-amber-50 px-2 py-1 text-[10px] font-medium text-amber-700 sm:text-xs">{{ $leavePct }}%</span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 sm:h-10 sm:w-10">
                            <i class="fas fa-umbrella-beach text-base text-amber-600 sm:text-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1">
                    <div class="h-1.5 w-full rounded-full bg-slate-100">
                        <div class="h-1.5 rounded-full bg-gradient-to-r from-amber-500 to-amber-700" style="width: {{ $leavePct }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Absent --}}
        <div class="group relative transform cursor-pointer duration-300 transition hover:-translate-y-1">
            <div class="absolute -inset-0.5 rounded-2xl bg-gradient-to-r from-rose-600 to-rose-400 opacity-30 blur transition duration-300 group-hover:opacity-100"></div>
            <div class="relative rounded-2xl bg-white p-4 shadow-lg transition-all duration-300 hover:shadow-xl sm:p-6">
                @php
                $absentPct = $summary['total'] > 0 ? round(($summary['absent'] / $summary['total']) * 100) : 0;
                @endphp
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-medium text-slate-500 sm:text-sm">Absent</p>
                        <h3 class="mt-1 text-2xl font-bold text-[#173f34] sm:text-3xl">{{ $summary['absent'] }}</h3>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="rounded-full bg-rose-50 px-2 py-1 text-[10px] font-medium text-rose-700 sm:text-xs">{{ $absentPct }}%</span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-rose-100 sm:h-10 sm:w-10">
                            <i class="fas fa-user-slash text-base text-rose-600 sm:text-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1">
                    <div class="h-1.5 w-full rounded-full bg-slate-100">
                        <div class="h-1.5 rounded-full bg-gradient-to-r from-rose-500 to-rose-700" style="width: {{ $absentPct }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pegawai Bertugas Hari Ini --}}
    <div class="space-y-2 bg-[#edf5ee] mt-4 p-4 rounded-lg">
        <div class="flex flex-col gap-3 rounded-lg bg-gradient-to-r from-[#173f34] to-[#2a684f] px-3 py-3 shadow-md sm:flex-row sm:items-center sm:justify-between sm:px-6 sm:py-4">
            <div class="flex items-center gap-2 text-white">
                <i class="fa-solid fa-users text-sm sm:text-base"></i>
                <span class="text-sm font-semibold sm:text-base">Pegawai Bertugas Hari Ini</span>
            </div>

            @if ($showFilter)
            <form method="GET" class="w-full sm:w-auto">
                <select name="department_id" onchange="this.form.submit()"
                    class="w-full rounded-lg border-0 px-3 py-2 text-sm shadow-sm focus:ring-2 focus:ring-[#dfeee1] sm:w-auto">
                    @if ($isGlobalRole)
                    <option value="" {{ !$departmentId ? 'selected' : '' }}>Semua Departemen</option>
                    @endif
                    @foreach ($departmentsForFilter as $department)
                    <option value="{{ $department->id }}" {{ $departmentId == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                    @endforeach
                </select>
            </form>
            @elseif ($departmentsForFilter->isNotEmpty())
            <span class="text-sm font-medium text-[#dfeee1] sm:text-sm">{{ $departmentsForFilter->first()->name }}</span>
            @endif
        </div>

        @if ($departmentsForFilter->isEmpty() && !$isGlobalRole)
        <div class="rounded-xl border border-[#dfeee1] bg-white p-10 text-center text-sm text-slate-400">
            <i class="fa-solid fa-building text-2xl mb-2 block"></i>
            Anda belum memiliki penempatan departemen.
        </div>
        @elseif ($expectedToday->isEmpty())
        <div class="rounded-xl border border-[#dfeee1] bg-white p-10 text-center text-sm text-slate-400">
            <i class="fa-solid fa-calendar-xmark text-2xl mb-2 block"></i>
            Tidak ada pegawai yang dijadwalkan hari ini.
        </div>
        @else
        <div class="space-y-4">
            @foreach ($expectedToday as $group)
            <div>
                <div class="mb-2 flex flex-col gap-1 px-1 sm:flex-row sm:items-center sm:justify-between">
                    <span class="text-[11px] font-bold uppercase text-[#173f34] sm:text-xs">
                        {{ $group['shift']->name }} ({{ \Carbon\Carbon::parse($group['shift']->start_time)->format('H:i') }})
                    </span>
                    <span class="text-[11px] text-slate-500 sm:text-xs">
                        {{ $group['checked_in_count'] }}/{{ $group['employees']->count() }} hadir
                    </span>
                </div>
                <div class="space-y-2">
                    @foreach ($group['employees'] as $entry)
                    <div class="flex flex-col gap-2 rounded-xl border border-[#dfeee1] bg-white p-3 shadow-sm transition hover:border-[#dfeee1] hover:shadow-md sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#edf5ee] text-xs font-bold text-[#173f34]">
                                {{ collect(explode(' ', $entry['employee']->name))->map(fn($w) => $w[0] ?? '')->take(2)->implode('') }}
                            </div>
                            <p class="text-sm font-semibold text-[#173f34]">{{ $entry['employee']->name }}</p>
                        </div>

                        @if ($entry['checked_in_at'])
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-semibold text-emerald-700 sm:px-3 sm:text-xs">
                            <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Hadir {{ $entry['checked_in_at'] }}
                        </span>
                        @else
                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-[10px] font-semibold text-amber-700 sm:px-3 sm:text-xs">
                            <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                            Belum Hadir
                        </span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@endsection