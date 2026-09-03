@extends('shared::layouts.app')

@section('title', 'Dashboard Pegawai')

@section('content')
<div class="mx-auto max-w-7xl px-3 py-4 sm:px-6 sm:py-8">

    {{-- ================= HEADER GREETING ================= --}}
    <div class="mb-5 rounded-2xl bg-gradient-to-br from-[#042A22] via-[#0F5C48] to-[#1B7A5C] p-5 text-white shadow-sm sm:p-6">
        <p class="text-sm text-white/70">Selamat datang,</p>
        <h1 class="mt-1 text-xl font-bold sm:text-2xl">{{ $employee->name }}</h1>
        <p class="mt-1 text-sm text-white/70">
            {{ $employee->currentPosition()?->name ?? '-' }}
            @if ($employee->currentDepartment())
            &bull; {{ $employee->currentDepartment()->name }}
            @endif
        </p>
        <p class="mt-3 text-xs text-white/60">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- ================= MENU CEPAT ================= --}}
    <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-black/5 sm:p-5 mb-5">
        <h2 class="mb-3 text-sm font-bold text-slate-800">Menu Cepat</h2>
        <div class="grid grid-cols-3 gap-3">

            <a href="{{ route('attendance.emergency-request.create') }}" class="flex flex-col items-center gap-2 rounded-xl border border-slate-100 p-3 text-center transition hover:border-[#A9C23F] hover:bg-[#A9C23F]/5">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-rose-50 text-rose-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-8.25 3.75h.008v.008h-.008v-.008z" />
                    </svg>
                </span>
                <span class="text-xs font-semibold text-slate-700">Presensi Darurat</span>
            </a>

            <a href="#" class="flex flex-col items-center gap-2 rounded-xl border border-slate-100 p-3 text-center transition hover:border-[#A9C23F] hover:bg-[#A9C23F]/5">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#0F5C48]/10 text-[#0F5C48]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3-15H6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 006 21h12a2.25 2.25 0 002.25-2.25V8.25L15 3z" />
                    </svg>
                </span>
                <span class="text-xs font-semibold text-slate-700">Ajukan Cuti</span>
            </a>

            <a href="#" class="flex flex-col items-center gap-2 rounded-xl border border-slate-100 p-3 text-center transition hover:border-[#A9C23F] hover:bg-[#A9C23F]/5">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#A9C23F]/15 text-[#6B8E2F]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </span>
                <span class="text-xs font-semibold text-slate-700">Jadwal Bulanan</span>
            </a>

        </div>
    </div>

    {{-- ================= CHECK-IN / CHECK-OUT + STATUS ================= --}}
    @php
    $checkInTime = $attendance['check_in_time'] ?? null;
    $checkOutTime = $attendance['check_out_time'] ?? null;
    $statusName = $attendance['attendance_status'] ?? null;

    $statusLabel = $statusName
    ?? ($attendance && $attendance['status'] === 'checked_in' ? 'Belum Check-Out' : ($attendance ? 'Menunggu Review' : 'Belum Presensi'));

    $statusColor = match (true) {
    $statusName === 'Terlambat' => 'amber',
    $statusName === 'Cuti' => 'sky',
    $statusName === 'Pulang Cepat' => 'orange',
    $statusName === 'Hadir' => 'emerald',
    !$attendance => 'slate',
    default => 'amber',
    };

    $statusColorMap = [
    'emerald' => ['bg-emerald-100', 'text-emerald-700', 'bg-emerald-500'],
    'amber' => ['bg-amber-100', 'text-amber-700', 'bg-amber-500'],
    'rose' => ['bg-rose-100', 'text-rose-700', 'bg-rose-500'],
    'sky' => ['bg-sky-100', 'text-sky-700', 'bg-sky-500'],
    'orange' => ['bg-orange-100', 'text-orange-700', 'bg-orange-500'],
    'slate' => ['bg-slate-100', 'text-slate-500', 'bg-slate-400'],
    ];
    [$badgeBg, $badgeText, $dotColor] = $statusColorMap[$statusColor];
    @endphp

    <div class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-3">

        <div class="rounded-2xl bg-[#F7FAF9] p-4 shadow-sm ring-1 ring-black/5">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-[#0F5C48]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Check-In
            </div>
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ $checkInTime ?? '--:--' }}</p>
            <p class="mt-0.5 text-xs text-slate-500">
                {{ $checkInTime ? ($todayShift['name'] ? 'Shift ' . $todayShift['name'] : 'Sudah check-in') : 'Belum check-in' }}
            </p>
        </div>

        <div class="rounded-2xl bg-[#F7FAF9] p-4 shadow-sm ring-1 ring-black/5">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-[#0F5C48]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Check-Out
            </div>
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ $checkOutTime ?? '--:--' }}</p>
            <p class="mt-0.5 text-xs text-slate-500">
                {{ $checkOutTime ? 'Selesai' : ($checkInTime ? 'Belum check-out' : '-') }}
            </p>
        </div>

        <div class="rounded-2xl bg-[#F7FAF9] p-4 shadow-sm ring-1 ring-black/5">
            <div class="text-xs font-semibold uppercase tracking-wide text-[#0F5C48]">Status Hari Ini</div>
            <div class="mt-2 inline-flex items-center gap-1.5 rounded-full {{ $badgeBg }} px-3 py-1 text-sm font-bold {{ $badgeText }}">
                <span class="h-2 w-2 rounded-full {{ $dotColor }}"></span>
                {{ $statusLabel }}
            </div>
            <p class="mt-2 text-xs text-slate-500">
                @if ($todayShift['is_libur'])
                Hari libur
                @elseif ($todayShift['name'])
                Shift {{ $todayShift['name'] }} &bull; {{ $todayShift['start_time'] }} - {{ $todayShift['end_time'] }}
                @else
                Belum ada jadwal shift
                @endif
            </p>
        </div>

    </div>

    {{-- ================= STATISTIK BULANAN ================= --}}
    <div class="mb-5 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-black/5 sm:p-5">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800">Statistik Kehadiran &bull; {{ now()->translatedFormat('F Y') }}</h2>
        </div>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            <div class="rounded-xl bg-emerald-50 p-3 text-center">
                <p class="text-2xl font-extrabold text-emerald-700">{{ $monthlyStats['hadir'] }}</p>
                <p class="mt-0.5 text-xs font-medium text-emerald-700/70">Hadir</p>
            </div>
            <div class="rounded-xl bg-amber-50 p-3 text-center">
                <p class="text-2xl font-extrabold text-amber-700">{{ $monthlyStats['terlambat'] }}</p>
                <p class="mt-0.5 text-xs font-medium text-amber-700/70">Terlambat</p>
            </div>
            <div class="rounded-xl bg-[#A9C23F]/15 p-3 text-center">
                <p class="text-2xl font-extrabold text-[#6B8E2F]">{{ $monthlyStats['cuti'] }}</p>
                <p class="mt-0.5 text-xs font-medium text-[#6B8E2F]/80">Cuti</p>
            </div>
            <div class="rounded-xl bg-rose-50 p-3 text-center">
                <p class="text-2xl font-extrabold text-rose-700">{{ $monthlyStats['absen'] }}</p>
                <p class="mt-0.5 text-xs font-medium text-rose-700/70">Tidak Hadir</p>
            </div>
        </div>
    </div>

    {{-- ================= SHIFT SEKARANG & BESOK ================= --}}
    <div class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-2">

        <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-black/5">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Shift Hari Ini</p>
            <div class="mt-2 flex items-center justify-between">
                <div>
                    @if ($todayShift['is_libur'])
                    <p class="text-lg font-bold text-slate-800">Libur</p>
                    @elseif ($todayShift['name'])
                    <p class="text-lg font-bold text-slate-800">{{ $todayShift['name'] }}</p>
                    <p class="text-sm text-slate-500">{{ $todayShift['start_time'] }} &ndash; {{ $todayShift['end_time'] }}</p>
                    @else
                    <p class="text-lg font-bold text-slate-400">Belum Terjadwal</p>
                    @endif
                </div>
                @unless ($todayShift['is_libur'])
                <span class="rounded-full bg-[#0F5C48]/10 px-3 py-1 text-xs font-bold text-[#0F5C48]">Aktif</span>
                @endunless
            </div>
        </div>

        <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-black/5">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Shift Besok</p>
            <div class="mt-2 flex items-center justify-between">
                <div>
                    @if ($tomorrowShift['is_libur'])
                    <p class="text-lg font-bold text-slate-800">Libur</p>
                    @elseif ($tomorrowShift['name'])
                    <p class="text-lg font-bold text-slate-800">{{ $tomorrowShift['name'] }}</p>
                    <p class="text-sm text-slate-500">{{ $tomorrowShift['start_time'] }} &ndash; {{ $tomorrowShift['end_time'] }}</p>
                    @else
                    <p class="text-lg font-bold text-slate-400">Belum Terjadwal</p>
                    @endif
                </div>
                @unless ($tomorrowShift['is_libur'])
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">Terjadwal</span>
                @endunless
            </div>
        </div>

    </div>

</div>
@endsection