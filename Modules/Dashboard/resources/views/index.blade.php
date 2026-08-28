@extends('shared::layouts.app')

@section('title', 'Dashboard Page')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="bg-slate-50">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h1 class="text-2xl font-bold tracking-tight text-sky-950">
                Dashboard
            </h1>
        </div>

        <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

            {{-- Total Employees --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Total Pegawai Terdaftar
                        </p>

                        <h3 class="mt-2 text-3xl font-bold text-sky-950">
                            {{ number_format($stats['total']) }}
                        </h3>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-200 text-sky-950">
                        <i class="fa-solid fa-users text-lg"></i>
                    </div>

                </div>
            </div>

            {{-- Total Pegawai Aktif --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Total Pegawai Aktif
                        </p>

                        <h3 class="mt-2 text-3xl font-bold text-sky-950">
                            {{ number_format($stats['aktif']) }}
                        </h3>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-200 text-sky-950">
                        <i class="fa-solid fa-users text-lg"></i>
                    </div>

                </div>
            </div>

            {{-- Total Pegawai Tidak Aktif --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Total Pegawai Tidak Aktif
                        </p>

                        <h3 class="mt-2 text-3xl font-bold text-sky-950">
                            {{ number_format($stats['nonaktif']) }}
                        </h3>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-200 text-sky-950">
                        <i class="fa-solid fa-users text-lg"></i>
                    </div>

                </div>
            </div>
        </div>

        @if ($pendingLeaveCount > 0 || $pendingEmergencyCount > 0 || $pendingSpCandidateCount > 0)
        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                        <i class="fa-solid fa-bell text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-amber-900">
                            Ada yang perlu perhatian Anda
                        </h3>
                        <p class="mt-0.5 text-sm text-amber-700">
                            @php
                            $parts = [];
                            if ($pendingLeaveCount > 0) $parts[] = "{$pendingLeaveCount} pengajuan cuti";
                            if ($pendingEmergencyCount > 0) $parts[] = "{$pendingEmergencyCount} presensi darurat";
                            if ($pendingSpCandidateCount > 0) $parts[] = "{$pendingSpCandidateCount} SP Candidate";
                            @endphp
                            {{ implode(', ', $parts) }} menunggu {{ count($parts) > 1 ? 'perhatian' : 'persetujuan' }}.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    @if ($pendingLeaveCount > 0)
                    <a href="{{ route('leave.requests.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-amber-700">
                        <i class="fa-solid fa-calendar-check text-xs"></i>
                        Lihat Cuti ({{ $pendingLeaveCount }})
                    </a>
                    @endif

                    @if ($pendingEmergencyCount > 0)
                    <a href="{{ route('attendance.emergency.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-amber-700">
                        <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                        Lihat Darurat ({{ $pendingEmergencyCount }})
                    </a>
                    @endif

                    @if ($pendingSpCandidateCount > 0)
                    <a href="{{ route('schedule.sp-candidates.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-amber-700">
                        <i class="fa-solid fa-file-signature text-xs"></i>
                        Lihat SP Candidate ({{ $pendingSpCandidateCount }})
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div class="mb-8 grid grid-cols-1 gap-5 md:grid-cols-4">

            {{-- Leave --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-950">
                        <i class="fa-solid fa-calendar-days text-lg"></i>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">
                            Cuti / Izin Aktif
                        </p>

                        <p class="mt-1 text-2xl font-bold text-sky-950">
                            {{ number_format($stats['leave']) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Attendance --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Hadir Hari Ini
                        </p>

                        <h3 class="mt-2 text-3xl font-bold text-sky-950">
                            {{ number_format($stats['present']) }}
                        </h3>

                        <p class="mt-2 text-xs text-slate-500">
                            <span class="font-semibold text-emerald-600">
                                {{ $stats['present_pct'] }}%
                            </span>
                            dari total pegawai
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                        <i class="fa-solid fa-user-check text-lg"></i>
                    </div>

                </div>
            </div>


            {{-- Late --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Terlambat Hari Ini
                        </p>

                        <h3 class="mt-2 text-3xl font-bold text-sky-950">
                            {{ number_format($stats['late']) }}
                        </h3>

                        <p class="mt-2 text-xs text-slate-500">
                            <span class="font-semibold text-amber-600">
                                {{ $stats['late_pct'] }}%
                            </span>
                            dari total kehadiran
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                        <i class="fa-solid fa-clock text-lg"></i>
                    </div>

                </div>
            </div>

            {{-- Absent --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Tidak Hadir
                        </p>

                        <h3 class="mt-2 text-3xl font-bold text-sky-950">
                            {{ number_format($stats['absent']) }}
                        </h3>

                        <p class="mt-2 text-xs text-slate-500">
                            <span class="font-semibold text-rose-600">
                                {{ $stats['absent_pct'] }}%
                            </span>
                            dari total pegawai
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-100 text-rose-700">
                        <i class="fa-solid fa-user-xmark text-lg"></i>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        {{-- Quick Actions --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="font-semibold text-sky-950">
                    Menu Akses Cepat
                </h2>
                <p class="mt-1 text-xs text-slate-500">
                    Pintasan ke fitur yang sering digunakan
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4 p-6 sm:grid-cols-4">
                @forelse ($quickAccessMenus as $menu)
                <a href="{{ route($menu['route']) }}"
                    class="group flex flex-col items-center gap-2 rounded-xl border border-slate-100 p-4 text-center transition hover:border-sky-200 hover:bg-sky-50/50 hover:shadow-sm">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl {{ $menu['color'] }} transition group-hover:scale-105">
                        <i class="fa-solid {{ $menu['icon'] }}"></i>
                    </div>
                    <span class="text-xs font-medium text-slate-700">
                        {{ $menu['label'] }}
                    </span>
                </a>
                @empty
                <div class="col-span-full py-6 text-center text-sm text-slate-400">
                    Tidak ada menu akses cepat yang tersedia untuk akun Anda.
                </div>
                @endforelse
            </div>
        </div>

        <div class="xl:col-span-2 rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="font-semibold text-sky-950">
                        Ringkasan Kehadiran
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Statistik kehadiran pegawai dalam 7 hari terakhir
                    </p>
                </div>
            </div>

            <div class="p-6">
                <div class="flex h-64 items-end gap-3 border-b border-l border-slate-200 px-4 pb-0">
                    @foreach($chartData as $item)
                    <div class="group flex h-full flex-1 flex-col items-center justify-end gap-2">
                        <span class="text-xs font-medium text-slate-500 opacity-0 transition group-hover:opacity-100">
                            {{ $item['value'] }}
                        </span>
                        <div
                            class="w-full max-w-10 rounded-t-md bg-sky-200 transition-all duration-300 group-hover:bg-sky-950"
                            style="height: {{ $item['height'] }}"></div>
                        <span class="translate-y-6 text-xs text-slate-500">
                            {{ $item['day'] }}
                        </span>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-2">

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-100 px-6 py-5">

                <h2 class="font-semibold text-sky-950">
                    Distribusi Pegawai
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Berdasarkan unit kerja
                </p>

            </div>

            <div class="space-y-5 p-6">

                @forelse ($departmentDistribution as $item)

                <div>

                    <div class="mb-2 flex items-center justify-between">

                        <span class="text-sm font-medium text-slate-700">
                            {{ $item['name'] }}
                        </span>

                        <span class="text-xs text-slate-500">
                            {{ $item['total'] }} pegawai
                        </span>

                    </div>

                    <div class="h-2 overflow-hidden rounded-full bg-slate-100">

                        <div
                            class="h-full rounded-full bg-sky-950"
                            style="width: {{ $item['percent'] }}"></div>

                    </div>

                </div>

                @empty
                <p class="text-sm text-slate-400 text-center py-6">Belum ada data departemen.</p>
                @endforelse

            </div>

        </div>

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="font-semibold text-sky-950">
                        Aktivitas Terbaru
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Aktivitas terbaru dalam sistem
                    </p>
                </div>

            </div>

            <div class="divide-y divide-slate-100">

                @forelse ($recentActivities as $activity)

                <div class="flex items-center gap-4 px-6 py-4">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $activity['color'] }}">
                        <i class="fa-solid {{ $activity['icon'] }}"></i>
                    </div>

                    <div class="min-w-0 flex-1">

                        <p class="truncate text-sm font-semibold text-slate-800">
                            {{ $activity['title'] }}
                        </p>

                        <p class="mt-1 truncate text-xs text-slate-500">
                            {{ $activity['description'] }}
                        </p>

                    </div>

                    <span class="whitespace-nowrap text-xs text-slate-400">
                        {{ $activity['time'] }}
                    </span>

                </div>

                @empty
                <div class="px-6 py-10 text-center text-sm text-slate-400">
                    Belum ada aktivitas terbaru.
                </div>
                @endforelse

            </div>

        </div>

    </div>

</div>
@endsection