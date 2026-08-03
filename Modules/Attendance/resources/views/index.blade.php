@extends('shared::layouts.app')

@section('title', 'Attendance Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="flex items-center bg-sky-100 p-4 rounded-lg">
        <i class="fa-solid fa-calendar-days text-sky-950 text-2xl mr-4"></i>
        <h1 class="text-xl font-semibold text-sky-950">Data Kehadiran {{ now()->translatedFormat('d F Y') }}</h1>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4 mt-4">

        {{-- Total Karyawan --}}
        <div class="relative group h-full transform transtion duration-300 hover:translate-y-[-2px] cursor-pointer">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-sky-950 to-sky-700 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-300"></div>
            <div class="relative bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 h-32 flex flex-col justify-center">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Total Karyawan</p>
                        <h3 class="text-3xl font-bold text-sky-950 mt-1">{{ $summary['total_employees'] }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-sky-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-sky-950 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hadir --}}
        <div class="relative group transform transtion duration-300 hover:translate-y-[-2px] cursor-pointer">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-600 to-emerald-400 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-300"></div>
            <div class="relative bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
                @php
                $presentPct = $summary['total_employees'] > 0 ? round(($summary['present'] / $summary['total_employees']) * 100) : 0;
                @endphp
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Hadir</p>
                        <h3 class="text-3xl font-bold text-sky-950 mt-1">{{ $summary['present'] }}</h3>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-xs text-emerald-700 bg-emerald-50 px-2 py-1 rounded-full">{{ $presentPct }}%</span>
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1">
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-700 h-1.5 rounded-full" style="width: {{ $presentPct }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cuti/Izin --}}
        <div class="relative group transform transtion duration-300 hover:translate-y-[-2px] cursor-pointer">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-600 to-amber-400 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-300"></div>
            <div class="relative bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
                @php
                $leavePct = $summary['total_employees'] > 0 ? round(($summary['on_leave'] / $summary['total_employees']) * 100) : 0;
                @endphp
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Cuti/Izin</p>
                        <h3 class="text-3xl font-bold text-sky-950 mt-1">{{ $summary['on_leave'] }}</h3>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-xs text-amber-700 bg-amber-50 px-2 py-1 rounded-full">{{ $leavePct }}%</span>
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-umbrella-beach text-amber-600 text-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1">
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <div class="bg-gradient-to-r from-amber-500 to-amber-700 h-1.5 rounded-full" style="width: {{ $leavePct }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Absent --}}
        <div class="relative group transform transtion duration-300 hover:translate-y-[-2px] cursor-pointer">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-rose-600 to-rose-400 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-300"></div>
            <div class="relative bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
                @php
                $absentPct = $summary['total_employees'] > 0 ? round(($summary['absent'] / $summary['total_employees']) * 100) : 0;
                @endphp
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Absent</p>
                        <h3 class="text-3xl font-bold text-sky-950 mt-1">{{ $summary['absent'] }}</h3>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-xs text-rose-700 bg-rose-50 px-2 py-1 rounded-full">{{ $absentPct }}%</span>
                        <div class="w-10 h-10 bg-rose-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-slash text-rose-600 text-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1">
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <div class="bg-gradient-to-r from-rose-500 to-rose-700 h-1.5 rounded-full" style="width: {{ $absentPct }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-sky-100 mt-4 p-4 rounded-lg flex items-center justify-between">
        <p class="text-sm font-medium text-sky-950">Aktivitas Check-in Hari Ini</p>
        <a href="{{ route('attendance.attendances.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-sky-950 hover:bg-sky-900 px-4 py-2 text-sm font-medium text-white transition duration-200">
            <i class="fa-solid fa-list text-sm"></i>
            Lihat Rekap Lengkap
        </a>
    </div>

    {{-- Attendance List --}}
    <div class="space-y-3 bg-sky-100 mt-4 p-4 rounded-lg">

        @forelse($recentAttendances as $attendance)
        @php
        $badge = match(true) {
        is_null($attendance->check_out_at) => ['Belum Check Out', 'amber'],
        $attendance->status === null => ['Perlu Review', 'slate'],
        $attendance->status->code === 'TERLAMBAT' => [$attendance->status->name, 'rose'],
        default => [$attendance->status->name, 'emerald'],
        };
        [$badgeLabel, $badgeColor] = $badge;
        @endphp

        <div class="rounded-xl border border-sky-200 bg-white p-4 shadow-sm transition hover:border-sky-300 hover:shadow-md">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div class="flex items-center gap-3 lg:w-1/4">
                    <x-shared::avatar
                        :src="$attendance->employee->photo ? asset('storage/' . $attendance->employee->photo) : null"
                        :name="$attendance->employee->name"
                        size="sm" />
                    <div>
                        <p class="font-semibold text-sky-950">{{ $attendance->employee->name }}</p>
                        <p class="text-xs text-gray-500">{{ $attendance->employee->employmentStatus->name ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 lg:w-1/5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 text-sky-950 ring-2 ring-sky-200">
                        <i class="fa-solid fa-right-to-bracket text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Check In</p>
                        <p class="font-semibold text-gray-800">{{ $attendance->check_in_at?->format('H:i') ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 lg:w-1/5">
                    @if($attendance->check_out_at)
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-950 text-white ring-2 ring-sky-200">
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Check Out</p>
                        <p class="font-semibold text-gray-800">{{ $attendance->check_out_at->format('H:i') }}</p>
                    </div>
                    @else
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Check Out</p>
                        <p class="font-semibold text-gray-400">-</p>
                    </div>
                    @endif
                </div>

                <div class="lg:w-1/5 lg:text-right">
                    <span class="inline-flex items-center rounded-full bg-{{ $badgeColor }}-100 px-3 py-1 text-xs font-semibold text-{{ $badgeColor }}-700">
                        <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-{{ $badgeColor }}-500"></span>
                        {{ $badgeLabel }}
                    </span>
                </div>

            </div>
        </div>
        @empty
        <div class="rounded-xl border border-sky-200 bg-white p-10 text-center text-sm text-slate-400">
            Belum ada aktivitas check-in hari ini.
        </div>
        @endforelse

    </div>

</div>
@endsection