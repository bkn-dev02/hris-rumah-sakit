@extends('shared::layouts.app')

@section('title', 'Attendance Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="flex items-center bg-sky-100 p-4 rounded-lg items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-calendar-days text-sky-950 text-2xl"></i>
            <h1 class="text-xl font-bold text-sky-950">Aktivitas Presensi Hari Ini, {{ now()->translatedFormat('d F Y') }}</h1>
        </div>
        <a href="{{ route('attendance.attendances.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-sky-950 hover:bg-sky-900 px-4 py-2 text-sm font-medium text-white transition duration-200">
            <i class="fa-solid fa-list text-sm"></i>
            Lihat Rekap Lengkap
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4 mt-4">

        {{-- Total Karyawan --}}
        <div class="relative group h-full transform transtion duration-300 hover:translate-y-[-2px] cursor-pointer">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-sky-950 to-sky-700 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-300"></div>
            <div class="relative bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 h-32 flex flex-col justify-center">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Total Karyawan</p>
                        <h3 class="text-3xl font-bold text-sky-950 mt-1">{{ $summary['total'] }}</h3>
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
                $presentPct = $summary['total'] > 0 ? round(($summary['present'] / $summary['total']) * 100) : 0;
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
                $leavePct = $summary['total'] > 0 ? round(($summary['on_leave'] / $summary['total']) * 100) : 0;
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
                $absentPct = $summary['total'] > 0 ? round(($summary['absent'] / $summary['total']) * 100) : 0;
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

    {{-- Attendance List --}}
    <div class="space-y-2 bg-sky-100 mt-4 p-4 rounded-lg">
        <div class="hidden lg:grid lg:grid-cols-12 lg:items-center rounded-lg bg-gradient-to-r from-sky-950 to-sky-800 px-6 py-4 shadow-md">

            <div class="col-span-3 flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-user"></i>
                <span>Info Karyawan</span>
            </div>

            <div class="col-span-2 flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-clock"></i>
                <span>Shift</span>
            </div>

            <div class="col-span-2 flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-right-to-bracket"></i>
                <span>Jam Masuk</span>
            </div>

            <div class="col-span-2 flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Jam Pulang</span>
            </div>

            <div class="col-span-2 flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-circle-check"></i>
                <span>Status Absensi</span>
            </div>

            <div class="col-span-1 text-end text-sm font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-gear"></i>
                Action
            </div>

        </div>

        @forelse($recentAttendances as $attendance)
        <div class="rounded-xl border border-sky-200 bg-white p-4 shadow-sm transition hover:border-sky-300 hover:shadow-md">
            <div class="flex flex-col gap-4 lg:grid lg:grid-cols-12 lg:items-center">

                <div class="flex items-center gap-3 lg:col-span-3">
                    <div>
                        <p class="font-semibold text-sky-950">{{ $attendance['employee_name'] }}</p>
                        <p class="text-xs text-gray-500">{{ $attendance['employee_position_name'] }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 lg:col-span-2">
                    <div>
                        <p class="font-bold uppercase text-sky-950 text-xs">{{ $attendance['shift_name'] }}</p>
                        <p class="text-xs text-gray-500">({{ $attendance['start_time'] }} - {{ $attendance['end_time'] }})</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 lg:col-span-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 text-sky-950 ring-2 ring-sky-500 overflow-hidden">
                        @if($attendance['check_in_photo_url'])
                        <img src="{{ $attendance['check_in_photo_url'] }}" alt="Foto check-in" class="h-full w-full object-cover">
                        @else
                        <i class="fa-solid fa-right-to-bracket text-sm"></i>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Check In</p>
                        <div class="flex text-sky-800 items-center gap-1">
                            <i class="fa-solid fa-clock"></i>
                            <p class="font-semibold">{{ $attendance['check_in_time'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 lg:col-span-2">
                    @if($attendance['check_out_time'])
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 text-sky-950 ring-2 ring-sky-500 overflow-hidden">
                        @if($attendance['check_out_photo_url'])
                        <img src="{{ $attendance['check_out_photo_url'] }}" alt="Foto check-out" class="h-full w-full object-cover">
                        @else
                        <i class="fa-solid fa-right-to-bracket text-sm"></i>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Check Out</p>
                        <p class="font-semibold text-gray-800">{{ $attendance['check_out_time'] }}</p>
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

                <div class="lg:col-span-2">
                    <span class="inline-flex items-center rounded-full bg-{{ $attendance['badge_color'] }}-100 px-3 py-1 text-xs font-semibold text-{{ $attendance['badge_color'] }}-700">
                        <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-{{ $attendance['badge_color'] }}-500"></span>
                        {{ $attendance['badge_label'] }}
                    </span>
                </div>

                <div class="lg:col-span-1 lg:text-end">
                    <a href="{{ route('attendance.attendances.show', $attendance['id']) }}" class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-br from-sky-950 via-sky-900 to-sky-800 hover:bg-gradient-to-tl px-4 py-2 text-sm font-medium text-white transition duration-200 transform translate-y-0 hover:translate-y-[-2px] cursor-pointer">
                        <i class="fa-solid fa-eye text-sm"></i>
                        Detail
                    </a>
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