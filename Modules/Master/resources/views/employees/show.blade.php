@extends('shared::layouts.app')

@section('title', $employee->name)

@section('content')
@php
$genderLabels = [
'male' => 'Laki-laki',
'female' => 'Perempuan',
];

$maritalLabels = [
'single' => 'Belum Menikah',
'married' => 'Menikah',
'divorced' => 'Cerai',
'widowed' => 'Janda/Duda',
];

$workDuration = $employee->hire_date ? $employee->hire_date->diff(now()) : null;
$workDurationText = $workDuration
? trim(implode(' ', array_filter([
$workDuration->y ? $workDuration->y . ' tahun' : null,
$workDuration->m ? $workDuration->m . ' bulan' : null,
$workDuration->d ? $workDuration->d . ' hari' : null,
])))
: '-';
@endphp
<div class="max-w-7xl mx-auto px-6 py-8">

    @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('success') }}
    </div>
    @endif

    <div class="flex items-center gap-4">
        <a href="{{ route('master.employees.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#1f4d3d] hover:bg-[#173f34] transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-xs md:text-md text-[#edf5ee]"></i>
        </a>
        <h1 class="text-md md:text-xl font-bold text-[#1f4d3d]">Detail Employee</h1>
    </div>

    <div class="mt-6 rounded-xl border border-[#dfeee1] bg-[#edf5ee] p-6 shadow-md">
        <div class="flex flex-col gap-6 lg:flex-row">
            <div class="flex shrink-0 justify-center lg:justify-start">
                <div class="rounded-2xl bg-[#2a684f] p-2 shadow-md ring-1 ring-[#dfeee1] h-42 w-42 lg:h-50 lg:w-50">
                    <img src="{{ $employee->photo ? asset('storage/' . $employee->photo) : null }}" alt="{{ $employee->name }}" class="h-full w-full rounded-xl object-cover" />
                </div>
            </div>

            {{-- Informasi Karyawan --}}
            <div class="min-w-0 flex-1">

                {{-- Header informasi + tombol edit --}}
                <div class="flex items-start justify-between gap-4">

                    <div>
                        <h2 class="text-xl font-bold text-[#1f4d3d]">
                            {{ $employee->name }}
                        </h2>

                        <p class="mt-1 text-sm text-[#2a684f]">
                            {{ $employee->employee_number }}
                        </p>
                    </div>

                    {{-- Tombol Edit --}}
                    <a
                        href="{{ route('master.employees.edit', $employee->slug) }}"
                        class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-gradient-to-br from-[#173f34] via-[#1f4d3d] to-[#2a684f] px-4 py-2.5 text-sm font-medium text-white shadow-md transition duration-200 hover:-translate-y-0.5 hover:shadow-lg">

                        <i class="fa-solid fa-pen text-xs"></i>
                        Edit Data

                    </a>

                </div>



                {{-- Detail --}}
                <div class="mt-5 border-t border-[#dfeee1] pt-5">

                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-3">

                        {{-- Posisi --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-[#2a684f]">
                                Posisi
                            </p>

                            <p class="mt-1 font-medium text-[#1f4d3d]">
                                {{ $employee->position ?? '-' }}
                            </p>
                        </div>

                        {{-- Status Kepegawaian --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-[#2a684f]">
                                Status Kepegawaian
                            </p>

                            <div class="flex flex-wrap items-center gap-2">
                                <p class="mt-1 font-medium text-[#1f4d3d]">
                                    {{ $employee->employmentStatus->name ?? '-' }}
                                </p>
                                <div class="">
                                    @if($employee->is_active)
                                    <x-shared::badge variant="success" dot>
                                        Aktif
                                    </x-shared::badge>
                                    @else
                                    <x-shared::badge variant="secondary" dot>
                                        Nonaktif
                                    </x-shared::badge>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-[#2a684f]">
                                Jenis Kelamin
                            </p>

                            <p class="mt-1 font-medium text-[#1f4d3d]">
                                {{ $genderLabels[$employee->gender] ?? '-' }}
                            </p>
                        </div>

                        {{-- Tanggal Bergabung --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-[#2a684f]">
                                Tanggal Bergabung
                            </p>

                            <p class="mt-1 font-medium text-[#1f4d3d]">
                                {{ $employee->hire_date?->format('d M Y') ?? '-' }}
                            </p>
                        </div>

                        {{-- Durasi Bekerja --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-[#2a684f]">
                                Durasi Bekerja
                            </p>

                            <p class="mt-1 font-medium text-[#1f4d3d]">
                                {{ $workDurationText }}
                            </p>
                        </div>

                        {{-- Tempat & Tanggal Lahir --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-[#2a684f]">
                                Tempat & Tanggal Lahir
                            </p>

                            <p class="mt-1 font-medium text-[#1f4d3d]">
                                {{ $employee->place_of_birth ?? '-' }}, {{ $employee->date_of_birth?->format('d M Y') ?? '-' }}
                            </p>
                        </div>

                        {{-- Profesi --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-[#2a684f]">
                                Profesi
                            </p>

                            <p class="mt-1 font-medium text-[#1f4d3d]">
                                {{ $employee->profession ?? '-' }}
                            </p>
                        </div>

                        {{-- NIK --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-[#2a684f]">
                                NIK
                            </p>

                            <p class="mt-1 font-medium text-[#1f4d3d]">
                                {{ $employee->national_id_number ?? '-' }}
                            </p>
                        </div>

                        {{-- Status Pernikahan --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-[#2a684f]">
                                Status Pernikahan
                            </p>

                            <p class="mt-1 font-medium text-[#1f4d3d]">
                                {{ $maritalLabels[$employee->marital_status] ?? '-' }}
                            </p>
                        </div>

                        {{-- Telepon --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-[#2a684f]">
                                Telepon
                            </p>

                            <p class="mt-1 font-medium text-[#1f4d3d]">
                                {{ $employee->phone ?? '-' }}
                            </p>
                        </div>

                        {{-- Pendidikan Terakhir --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-[#2a684f]">
                                Pendidikan Terakhir
                            </p>

                            <p class="mt-1 font-medium text-[#1f4d3d]">
                                {{ $employee->education_level ?? '-' }} {{ $employee->education_major ?? '-' }}
                            </p>
                        </div>

                        {{-- Alamat --}}
                        <div class="sm:col-span-2">
                            <p class="text-xs font-medium uppercase tracking-wide text-[#2a684f]">
                                Alamat
                            </p>

                            <p class="mt-1 font-medium text-[#1f4d3d]">
                                {{ $employee->address ?? '-' }}
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Penempatan --}}
        <div class="flex flex-col rounded-xl border border-[#dfeee1] bg-[#edf5ee] p-6 shadow-md">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#1f4d3d] text-white shadow-sm">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Penempatan Saat Ini</h3>
                        <p class="text-sm text-slate-500">Informasi unit kerja terbaru</p>
                    </div>
                </div>

                <a href="{{ route('master.employees.placements.index', $employee->slug) }}" class="inline-flex items-center gap-2 rounded-full border border-[#dfeee1] bg-white px-3 py-1.5 text-xs font-semibold text-[#1f4d3d] shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-[#edf5ee]">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Lihat Riwayat
                </a>
            </div>

            @php $placement = $employee->currentPlacement(); @endphp

            <div class="mt-5 flex flex-1 flex-col">
                @if($placement)
                <div class="rounded-xl border border-[#dfeee1] bg-white p-4 mb-3">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Department</p>
                    <p class="mt-1 font-semibold text-slate-800">{{ $placement->department->name }}</p>
                    <p class="mt-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Posisi</p>
                    <p class="mt-1 font-semibold text-slate-800">{{ $placement->position->name }}</p>
                    <div class="mt-3 inline-flex items-center rounded-full bg-[#edf5ee] px-3 py-1 text-xs font-medium text-[#1f4d3d]">
                        <i class="fa-solid fa-calendar-day mr-2"></i>
                        Sejak {{ $placement->start_date->format('d M Y') }}
                    </div>
                </div>
                @else
                <div class="rounded-xl border border-dashed border-[#dfeee1] bg-white p-4 text-sm text-slate-400">
                    Belum pernah ditempatkan.
                </div>
                @endif

                <a href="{{ route('master.employees.placements.create', $employee->slug) }}" class="mt-auto inline-flex items-center gap-2 rounded-full bg-[#1f4d3d] px-4 py-2.5 text-sm font-medium text-white shadow-md transition duration-200 hover:-translate-y-0.5 hover:bg-[#173f34]">
                    <i class="fa fa-right-left text-sm"></i>
                    Tempatkan / Mutasi
                </a>
            </div>
        </div>

        {{-- Shift --}}
        <div class="flex flex-col rounded-xl border border-[#dfeee1] bg-white p-6 shadow-md">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#1f4d3d] text-white shadow-sm">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Jadwal Shift Saat Ini</h3>
                        <p class="text-sm text-slate-500">Informasi jadwal kerja terbaru</p>
                    </div>
                </div>

                <a href="{{ route('master.employees.shift-schedules.index', $employee->slug) }}" class="inline-flex items-center gap-2 rounded-full border border-[#dfeee1] bg-[#edf5ee] px-3 py-1.5 text-xs font-semibold text-[#1f4d3d] shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-[#dfeee1]">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Lihat Riwayat
                </a>
            </div>

            @php $schedule = $employee->currentShiftSchedule(); @endphp

            <div class="mt-5 flex flex-1 flex-col">
                @if($schedule)
                <div class="rounded-xl border border-[#dfeee1] bg-[#f8fbf8] p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Shift</p>
                    <p class="mt-1 font-semibold text-slate-800">{{ $schedule->shift->name }} ({{ $schedule->shift->start_time }} - {{ $schedule->shift->end_time }})</p>
                    <div class="mt-3 inline-flex items-center rounded-full bg-white px-3 py-1 text-xs font-medium text-[#2a684f] shadow-sm">
                        <i class="fa-solid fa-calendar-day mr-2"></i>
                        Sejak {{ $schedule->start_date->format('d M Y') }}
                    </div>
                </div>
                @else
                <div class="rounded-xl border border-dashed border-[#dfeee1] bg-[#f8fbf8] p-4 text-sm text-slate-400">
                    Belum ada jadwal shift.
                </div>
                @endif

                <a href="{{ route('master.employees.shift-schedules.create', $employee->slug) }}" class="mt-auto inline-flex items-center gap-2 rounded-full bg-[#173f34] px-4 py-2.5 text-sm font-medium text-white shadow-md transition duration-200 hover:-translate-y-0.5 hover:bg-[#173f34]">
                    <i class="fa fa-clock text-sm"></i>
                    Jadwalkan Shift
                </a>
            </div>
        </div>

    </div>

    {{-- Kuota Cuti --}}
    <div class="mt-6 overflow-hidden rounded-xl border border-[#dfeee1] bg-white shadow-md">
        <div class="border-b border-[#dfeee1] bg-[#f8fbf8] px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#173f34] text-white shadow-sm">
                    <i class="fa-solid fa-umbrella-beach"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Kuota Cuti {{ $quotaYear }}</h3>
                    <p class="text-sm text-slate-500">Cuti yang sudah di-assign, dipakai, dan tersisa</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto p-4 sm:p-6">
            @if ($leaveQuotas->isNotEmpty())
            <table class="w-full min-w-[620px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-400">
                        <th class="px-3 py-3 font-semibold">Jenis Cuti</th>
                        <th class="px-3 py-3 text-center font-semibold">Hak Cuti</th>
                        <th class="px-3 py-3 text-center font-semibold">Sudah Dipakai</th>
                        <th class="px-3 py-3 text-center font-semibold">Sisa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($leaveQuotas as $quota)
                    <tr class="transition hover:bg-[#f8fbf8]/60">
                        <td class="px-3 py-3 font-medium text-slate-700">
                            {{ $quota['leave_type']?->name ?? 'Jenis cuti tidak ditemukan' }}
                        </td>
                        <td class="px-3 py-3 text-center text-slate-600">
                            {{ $quota['quota_days'] }} hari
                        </td>
                        <td class="px-3 py-3 text-center text-slate-600">
                            {{ $quota['used_days'] }} hari
                        </td>
                        <td class="px-3 py-3 text-center">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                {{ $quota['remaining_days'] > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                {{ $quota['remaining_days'] }} hari
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="rounded-xl border border-dashed border-[#dfeee1] bg-[#f8fbf8] p-8 text-center text-sm text-slate-500">
                Belum ada kuota cuti yang di-assign untuk tahun {{ $quotaYear }}.
            </div>
            @endif
        </div>
    </div>

    {{-- Riwayat Absensi --}}
    <div class="mt-6 overflow-hidden rounded-xl border border-[#dfeee1] bg-white shadow-md">
        <div class="border-b border-[#dfeee1] bg-[#f8fbf8] px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#173f34] text-white shadow-sm">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Riwayat Absensi</h3>
                    <p class="text-sm text-slate-500">Seluruh data absensi pegawai</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto p-4 sm:p-6">
            @if ($attendances->isNotEmpty())
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-400">
                        <th class="px-3 py-3 font-semibold">Tanggal</th>
                        <th class="px-3 py-3 font-semibold">Shift</th>
                        <th class="px-3 py-3 font-semibold">Check In</th>
                        <th class="px-3 py-3 font-semibold">Check Out</th>
                        <th class="px-3 py-3 font-semibold">Status</th>
                        <th class="px-3 py-3 font-semibold">Sumber</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($attendances as $attendance)
                    @php
                    $attendanceStatus = $attendance->status;
                    $statusColor = match ($attendanceStatus?->code) {
                    'CUTI', 'LEAVE' => 'amber',
                    'HADIR' => 'emerald',
                    'TERLAMBAT', 'PULANG_CEPAT' => 'rose',
                    default => 'slate',
                    };
                    @endphp
                    <tr class="transition hover:bg-[#f8fbf8]/60">
                        <td class="whitespace-nowrap px-3 py-3 font-medium text-slate-700">
                            {{ $attendance->work_date?->format('d M Y') ?? '-' }}
                        </td>
                        <td class="px-3 py-3 text-slate-600">
                            {{ $attendance->shift?->name ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-3 text-slate-600">
                            {{ $attendance->checkIn?->checked_at?->format('d M Y H:i') ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-3 text-slate-600">
                            {{ $attendance->checkOut?->checked_at?->format('d M Y H:i') ?? '-' }}
                        </td>
                        <td class="px-3 py-3">
                            <span class="inline-flex items-center rounded-full bg-{{ $statusColor }}-50 px-2.5 py-1 text-xs font-semibold text-{{ $statusColor }}-700">
                                {{ $attendanceStatus?->name ?? 'Belum ditentukan' }}
                            </span>
                        </td>
                        <td class="px-3 py-3 text-slate-600">
                            {{ ucfirst($attendance->source ?? '-') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="rounded-xl border border-dashed border-[#dfeee1] bg-[#f8fbf8] p-8 text-center text-sm text-slate-500">
                Belum ada riwayat absensi.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
