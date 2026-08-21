@extends('shared::layouts.app')

@section('title', 'Dashboard Page')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="min-h-screen bg-slate-50">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h1 class="text-2xl font-bold tracking-tight text-sky-950">
                Dashboard
            </h1>
        </div>

        <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Total Employees --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Total Pegawai
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

        <div class="mb-8 grid grid-cols-1 gap-5 md:grid-cols-3">

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

        {{-- Attendance Overview --}}
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

                <button
                    type="button"
                    class="text-sm font-medium text-sky-900 hover:text-sky-950">
                    Lihat Detail
                    <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                </button>

            </div>

            <div class="p-6">

                {{-- Fake Chart --}}
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


        {{-- Employee Distribution --}}
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

                @foreach([
                ['name' => 'Keperawatan', 'total' => '420', 'percent' => '72%'],
                ['name' => 'Medis', 'total' => '280', 'percent' => '55%'],
                ['name' => 'Administrasi', 'total' => '210', 'percent' => '42%'],
                ['name' => 'Farmasi', 'total' => '145', 'percent' => '30%'],
                ['name' => 'Penunjang', 'total' => '193', 'percent' => '38%'],
                ] as $item)

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

                @endforeach

            </div>

        </div>

    </div>


    {{-- ============================================
        BOTTOM CONTENT
    ============================================= --}}
    <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-2">

        {{-- Recent Activities --}}
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

                <button
                    type="button"
                    class="text-sm font-medium text-sky-900 hover:text-sky-950">
                    Lihat Semua
                </button>

            </div>

            <div class="divide-y divide-slate-100">

                @foreach([
                [
                'icon' => 'fa-user-plus',
                'color' => 'bg-sky-100 text-sky-950',
                'title' => 'Pegawai baru ditambahkan',
                'description' => 'Siti Rahma bergabung sebagai Perawat',
                'time' => '10 menit lalu'
                ],
                [
                'icon' => 'fa-calendar-check',
                'color' => 'bg-emerald-100 text-emerald-700',
                'title' => 'Pengajuan cuti disetujui',
                'description' => 'Pengajuan cuti oleh Budi Santoso',
                'time' => '35 menit lalu'
                ],
                [
                'icon' => 'fa-clock',
                'color' => 'bg-amber-100 text-amber-700',
                'title' => 'Pengajuan lembur baru',
                'description' => 'Pengajuan lembur Unit Keperawatan',
                'time' => '1 jam lalu'
                ],
                [
                'icon' => 'fa-pen-to-square',
                'color' => 'bg-slate-100 text-slate-700',
                'title' => 'Data pegawai diperbarui',
                'description' => 'Data jabatan pegawai diperbarui',
                'time' => '2 jam lalu'
                ],
                ] as $activity)

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

                @endforeach

            </div>

        </div>


        {{-- Quick Actions --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-100 px-6 py-5">

                <h2 class="font-semibold text-sky-950">
                    Akses Cepat
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Akses fitur administrasi yang sering digunakan
                </p>

            </div>

            <div class="grid grid-cols-2 gap-4 p-6 sm:grid-cols-3">

                <a
                    href="#"
                    class="group rounded-xl border border-slate-200 p-4 transition hover:border-sky-200 hover:bg-sky-50">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-200 text-sky-950">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>

                    <p class="mt-3 text-sm font-semibold text-slate-700 group-hover:text-sky-950">
                        Tambah Pegawai
                    </p>
                </a>


                <a
                    href="#"
                    class="group rounded-xl border border-slate-200 p-4 transition hover:border-sky-200 hover:bg-sky-50">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-200 text-sky-950">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>

                    <p class="mt-3 text-sm font-semibold text-slate-700 group-hover:text-sky-950">
                        Kehadiran
                    </p>
                </a>


                <a
                    href="#"
                    class="group rounded-xl border border-slate-200 p-4 transition hover:border-sky-200 hover:bg-sky-50">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-200 text-sky-950">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>

                    <p class="mt-3 text-sm font-semibold text-slate-700 group-hover:text-sky-950">
                        Laporan
                    </p>
                </a>


                <a
                    href="#"
                    class="group rounded-xl border border-slate-200 p-4 transition hover:border-sky-200 hover:bg-sky-50">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-200 text-sky-950">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>

                    <p class="mt-3 text-sm font-semibold text-slate-700 group-hover:text-sky-950">
                        Pengguna
                    </p>
                </a>


                <a
                    href="#"
                    class="group rounded-xl border border-slate-200 p-4 transition hover:border-sky-200 hover:bg-sky-50">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-200 text-sky-950">
                        <i class="fa-solid fa-building"></i>
                    </div>

                    <p class="mt-3 text-sm font-semibold text-slate-700 group-hover:text-sky-950">
                        Organisasi
                    </p>
                </a>


                <a
                    href="#"
                    class="group rounded-xl border border-slate-200 p-4 transition hover:border-sky-200 hover:bg-sky-50">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-200 text-sky-950">
                        <i class="fa-solid fa-gear"></i>
                    </div>

                    <p class="mt-3 text-sm font-semibold text-slate-700 group-hover:text-sky-950">
                        Pengaturan
                    </p>
                </a>

            </div>

        </div>

    </div>

</div>
@endsection