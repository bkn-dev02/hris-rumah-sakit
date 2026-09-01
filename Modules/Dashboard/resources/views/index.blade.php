@extends('shared::layouts.app')

@section('title', 'Dashboard Page')

@section('content')
<div class="mx-auto max-w-7xl px-3 py-4 sm:px-6 sm:py-8">
    <div class="bg-[#edf5ee] p-4 rounded-xl shadow-sm mb-6">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h1 class="text-2xl font-bold tracking-tight text-[#1f4d3d]">
                Dashboard
            </h1>
        </div>

        <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

            {{-- Total Employees --}}
            <div class="rounded-xl border border-[#dfeee1] bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-[#4a665c]">
                            Total Pegawai Terdaftar
                        </p>

                        <h3 class="mt-2 text-3xl font-bold text-[#1f4d3d]">
                            {{ number_format($stats['total']) }}
                        </h3>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#dfeee1] text-[#1f4d3d]">
                        <i class="fa-solid fa-users text-lg"></i>
                    </div>

                </div>
            </div>

            {{-- Total Pegawai Aktif --}}
            <div class="rounded-xl border border-[#dfeee1] bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-[#4a665c]">
                            Total Pegawai Aktif
                        </p>

                        <h3 class="mt-2 text-3xl font-bold text-[#1f4d3d]">
                            {{ number_format($stats['aktif']) }}
                        </h3>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#dfeee1] text-[#1f4d3d]">
                        <i class="fa-solid fa-users text-lg"></i>
                    </div>

                </div>
            </div>

            {{-- Total Pegawai Tidak Aktif --}}
            <div class="rounded-xl border border-[#dfeee1] bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-[#4a665c]">
                            Total Pegawai Tidak Aktif
                        </p>

                        <h3 class="mt-2 text-3xl font-bold text-[#1f4d3d]">
                            {{ number_format($stats['nonaktif']) }}
                        </h3>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#dfeee1] text-[#1f4d3d]">
                        <i class="fa-solid fa-users text-lg"></i>
                    </div>

                </div>
            </div>
        </div>

        @if ($pendingLeaveCount > 0 || $pendingEmergencyCount > 0 || $pendingSpCandidateCount > 0)
        <div class="mb-6 rounded-xl border border-[#dfeee1] bg-[#edf7ef] p-5 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#dfeee1] text-[#1f4d3d]">
                        <i class="fa-solid fa-bell text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-[#1f4d3d]">
                            Ada yang perlu perhatian Anda
                        </h3>
                        <p class="mt-0.5 text-sm text-[#3c5f52]">
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
                        class="inline-flex items-center gap-2 rounded-lg bg-[#2a684f] px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-[#224f3f]">
                        <i class="fa-solid fa-calendar-check text-xs"></i>
                        Lihat Cuti ({{ $pendingLeaveCount }})
                    </a>
                    @endif

                    @if ($pendingEmergencyCount > 0)
                    <a href="{{ route('attendance.emergency.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-[#2a684f] px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-[#224f3f]">
                        <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                        Lihat Darurat ({{ $pendingEmergencyCount }})
                    </a>
                    @endif

                    @if ($pendingSpCandidateCount > 0)
                    <a href="{{ route('schedule.sp-candidates.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-[#2a684f] px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-[#224f3f]">
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
            <div class="rounded-xl border border-[#dfeee1] bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#edf5ee] text-[#1f4d3d]">
                        <i class="fa-solid fa-calendar-days text-lg"></i>
                    </div>

                    <div>
                        <p class="text-sm text-[#4a665c]">
                            Cuti / Izin Aktif
                        </p>

                        <p class="mt-1 text-2xl font-bold text-[#1f4d3d]">
                            {{ number_format($stats['leave']) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Attendance --}}
            <div class="rounded-xl border border-[#dfeee1] bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-[#4a665c]">
                            Hadir Hari Ini
                        </p>

                        <h3 class="mt-2 text-3xl font-bold text-[#1f4d3d]">
                            {{ number_format($stats['present']) }}
                        </h3>

                        <p class="mt-2 text-xs text-[#4a665c]">
                            <span class="font-semibold text-[#2a684f]">
                                {{ $stats['present_pct'] }}%
                            </span>
                            dari total pegawai
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#eaf5ee] text-[#2a684f]">
                        <i class="fa-solid fa-user-check text-lg"></i>
                    </div>

                </div>
            </div>


            {{-- Late --}}
            <div class="rounded-xl border border-[#dfeee1] bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-[#4a665c]">
                            Terlambat Hari Ini
                        </p>

                        <h3 class="mt-2 text-3xl font-bold text-[#1f4d3d]">
                            {{ number_format($stats['late']) }}
                        </h3>

                        <p class="mt-2 text-xs text-[#4a665c]">
                            <span class="font-semibold text-[#b97818]">
                                {{ $stats['late_pct'] }}%
                            </span>
                            dari total kehadiran
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#fff4dc] text-[#b97818]">
                        <i class="fa-solid fa-clock text-lg"></i>
                    </div>

                </div>
            </div>

            {{-- Absent --}}
            <div class="rounded-xl border border-[#dfeee1] bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-[#4a665c]">
                            Tidak Hadir
                        </p>

                        <h3 class="mt-2 text-3xl font-bold text-[#1f4d3d]">
                            {{ number_format($stats['absent']) }}
                        </h3>

                        <p class="mt-2 text-xs text-[#4a665c]">
                            <span class="font-semibold text-[#d75b5b]">
                                {{ $stats['absent_pct'] }}%
                            </span>
                            dari total pegawai
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#ffe9ea] text-[#d75b5b]">
                        <i class="fa-solid fa-user-xmark text-lg"></i>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        {{-- Quick Actions --}}
        <div class="rounded-xl border border-[#dfeee1] bg-white shadow-sm">
            <div class="border-b border-[#edf5ee] px-6 py-5">
                <h2 class="font-semibold text-[#1f4d3d]">
                    Menu Akses Cepat
                </h2>
                <p class="mt-1 text-xs text-[#4a665c]">
                    Pintasan ke fitur yang sering digunakan
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4 p-6 sm:grid-cols-4">
                @forelse ($quickAccessMenus as $menu)
                <a href="{{ route($menu['route']) }}"
                    class="group flex flex-col items-center gap-2 rounded-xl border border-[#edf5ee] p-4 text-center transition hover:border-[#bfe2c7] hover:bg-[#f1faf3] hover:shadow-sm">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl {{ $menu['color'] }} transition group-hover:scale-105">
                        <i class="fa-solid {{ $menu['icon'] }}"></i>
                    </div>
                    <span class="text-xs font-medium text-[#2a4d44]">
                        {{ $menu['label'] }}
                    </span>
                </a>
                @empty
                <div class="col-span-full py-6 text-center text-sm text-[#6c877d]">
                    Tidak ada menu akses cepat yang tersedia untuk akun Anda.
                </div>
                @endforelse
            </div>
        </div>

        <div class="rounded-xl border border-[#dfeee1] bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-[#edf5ee] px-6 py-5">
                <div>
                    <h2 class="font-semibold text-[#1f4d3d]">
                        Ringkasan Kehadiran
                    </h2>
                    <p class="mt-1 text-xs text-[#4a665c]">
                        Statistik kehadiran pegawai dalam 7 hari terakhir
                    </p>
                </div>
            </div>

            <div class="p-6">
                <div class="flex h-64 items-end gap-3 border-b border-l border-[#dfeee1] px-4 pb-0">
                    @foreach($chartData as $item)
                    <div class="group flex h-full flex-1 flex-col items-center justify-end gap-2">
                        <span class="text-xs font-medium text-[#4a665c] opacity-0 transition group-hover:opacity-100">
                            {{ $item['value'] }}
                        </span>
                        <div
                            class="w-full max-w-10 rounded-t-md bg-[#bfe2c7] transition-all duration-300 group-hover:bg-[#1f4d3d]"
                            style="height: {{ $item['height'] }}"></div>
                        <span class="translate-y-6 text-xs text-[#4a665c]">
                            {{ $item['day'] }}
                        </span>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-2">

        <div class="rounded-xl border border-[#dfeee1] bg-white shadow-sm">

            <div class="border-b border-[#edf5ee] px-6 py-5">

                <h2 class="font-semibold text-[#1f4d3d]">
                    Distribusi Pegawai
                </h2>

                <p class="mt-1 text-xs text-[#4a665c]">
                    Berdasarkan unit kerja
                </p>

            </div>

            <div class="space-y-5 p-6">

                @forelse ($departmentDistribution as $item)

                <div>

                    <div class="mb-2 flex items-center justify-between">

                        <span class="text-sm font-medium text-[#2a4d44]">
                            {{ $item['name'] }}
                        </span>

                        <span class="text-xs text-[#4a665c]">
                            {{ $item['total'] }} pegawai
                        </span>

                    </div>

                    <div class="h-2 overflow-hidden rounded-full bg-[#edf5ee]">
                        <div
                            class="h-full rounded-full bg-[#1f4d3d]"
                            style="width: {{ $item['percent'] }}"></div>
                    </div>

                </div>

                @empty
                <p class="py-6 text-center text-sm text-[#6c877d]">Belum ada data departemen.</p>
                @endforelse

            </div>

        </div>

        <div class="rounded-xl border border-[#dfeee1] bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-[#edf5ee] px-6 py-5">
                <div>
                    <h2 class="font-semibold text-[#1f4d3d]">
                        Aktivitas Terbaru
                    </h2>
                    <p class="mt-1 text-xs text-[#4a665c]">
                        Aktivitas terbaru dalam sistem
                    </p>
                </div>

            </div>

            <div class="divide-y divide-[#edf5ee]">

                @forelse ($recentActivities as $activity)

                <div class="flex items-center gap-4 px-6 py-4">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $activity['color'] }}">
                        <i class="fa-solid {{ $activity['icon'] }}"></i>
                    </div>

                    <div class="min-w-0 flex-1">

                        <p class="truncate text-sm font-semibold text-[#1f4d3d]">
                            {{ $activity['title'] }}
                        </p>

                        <p class="mt-1 truncate text-xs text-[#4a665c]">
                            {{ $activity['description'] }}
                        </p>

                    </div>

                    <span class="whitespace-nowrap text-xs text-[#6c877d]">
                        {{ $activity['time'] }}
                    </span>

                </div>

                @empty
                <div class="px-6 py-10 text-center text-sm text-[#6c877d]">
                    Belum ada aktivitas terbaru.
                </div>
                @endforelse

            </div>

        </div>

    </div>

</div>
@endsection