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
        <a href="{{ route('master.employees.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-sky-800 hover:bg-sky-900 transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-xs md:text-md text-sky-50"></i>
        </a>
        <h1 class="text-md md:text-xl font-bold text-sky-800">Detail Employee</h1>
    </div>

    <div class="mt-6 rounded-xl border border-sky-200 bg-sky-100 p-6 shadow-md">
        <div class="flex flex-col gap-6 lg:flex-row">
            <div class="flex shrink-0 justify-center lg:justify-start">
                <div class="rounded-2xl bg-sky-500 p-2 shadow-md ring-1 ring-sky-200 h-42 w-42 lg:h-50 lg:w-50">
                    <img src="{{ $employee->photo ? asset('storage/' . $employee->photo) : null }}" alt="{{ $employee->name }}" class="h-full w-full rounded-xl object-cover" />
                </div>
            </div>

            {{-- Informasi Karyawan --}}
            <div class="min-w-0 flex-1">

                {{-- Header informasi + tombol edit --}}
                <div class="flex items-start justify-between gap-4">

                    <div>
                        <h2 class="text-xl font-bold text-sky-950">
                            {{ $employee->name }}
                        </h2>

                        <p class="mt-1 text-sm text-sky-500">
                            {{ $employee->employee_number }}
                        </p>
                    </div>

                    {{-- Tombol Edit --}}
                    <a
                        href="{{ route('master.employees.edit', $employee->slug) }}"
                        class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-gradient-to-br from-sky-950 via-sky-900 to-sky-800 px-4 py-2.5 text-sm font-medium text-white shadow-md transition duration-200 hover:-translate-y-0.5 hover:shadow-lg">

                        <i class="fa-solid fa-pen text-xs"></i>
                        Edit Data

                    </a>

                </div>



                {{-- Detail --}}
                <div class="mt-5 border-t border-sky-200 pt-5">

                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-3">

                        {{-- Posisi --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-sky-600">
                                Posisi
                            </p>

                            <p class="mt-1 font-medium text-sky-900">
                                {{ $employee->position ?? '-' }}
                            </p>
                        </div>

                        {{-- Status Kepegawaian --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-sky-600">
                                Status Kepegawaian
                            </p>

                            <div class="flex flex-wrap items-center gap-2">
                                <p class="mt-1 font-medium text-sky-900">
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
                            <p class="text-xs font-medium uppercase tracking-wide text-sky-600">
                                Jenis Kelamin
                            </p>

                            <p class="mt-1 font-medium text-sky-900">
                                {{ $genderLabels[$employee->gender] ?? '-' }}
                            </p>
                        </div>

                        {{-- Tanggal Bergabung --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-sky-600">
                                Tanggal Bergabung
                            </p>

                            <p class="mt-1 font-medium text-sky-900">
                                {{ $employee->hire_date?->format('d M Y') ?? '-' }}
                            </p>
                        </div>

                        {{-- Durasi Bekerja --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-sky-600">
                                Durasi Bekerja
                            </p>

                            <p class="mt-1 font-medium text-sky-900">
                                {{ $workDurationText }}
                            </p>
                        </div>

                        {{-- Tempat & Tanggal Lahir --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-sky-600">
                                Tempat & Tanggal Lahir
                            </p>

                            <p class="mt-1 font-medium text-sky-900">
                                {{ $employee->place_of_birth ?? '-' }}, {{ $employee->date_of_birth?->format('d M Y') ?? '-' }}
                            </p>
                        </div>

                        {{-- NIK --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-sky-600">
                                NIK
                            </p>

                            <p class="mt-1 font-medium text-sky-900">
                                {{ $employee->national_id_number ?? '-' }}
                            </p>
                        </div>

                        {{-- Status Pernikahan --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-sky-600">
                                Status Pernikahan
                            </p>

                            <p class="mt-1 font-medium text-sky-900">
                                {{ $maritalLabels[$employee->marital_status] ?? '-' }}
                            </p>
                        </div>

                        {{-- Telepon --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-sky-600">
                                Telepon
                            </p>

                            <p class="mt-1 font-medium text-sky-900">
                                {{ $employee->phone ?? '-' }}
                            </p>
                        </div>

                        {{-- Pendidikan Terakhir --}}
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-sky-600">
                                Pendidikan Terakhir
                            </p>

                            <p class="mt-1 font-medium text-sky-900">
                                {{ $employee->education_level ?? '-' }} {{ $employee->education_major ?? '-' }}
                            </p>
                        </div>

                        {{-- Alamat --}}
                        <div class="sm:col-span-2">
                            <p class="text-xs font-medium uppercase tracking-wide text-sky-600">
                                Alamat
                            </p>

                            <p class="mt-1 font-medium text-sky-900">
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
        <div class="flex flex-col rounded-xl border border-sky-200 bg-sky-50 p-6 shadow-md">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-900 text-white shadow-sm">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Penempatan Saat Ini</h3>
                        <p class="text-sm text-slate-500">Informasi unit kerja terbaru</p>
                    </div>
                </div>

                <a href="{{ route('master.employees.placements.index', $employee->slug) }}" class="inline-flex items-center gap-2 rounded-full border border-sky-300 bg-white px-3 py-1.5 text-xs font-semibold text-sky-800 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-sky-100">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Lihat Riwayat
                </a>
            </div>

            @php $placement = $employee->currentPlacement(); @endphp

            <div class="mt-5 flex flex-1 flex-col">
                @if($placement)
                <div class="rounded-xl border border-sky-200 bg-white p-4 mb-3">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Department</p>
                    <p class="mt-1 font-semibold text-slate-800">{{ $placement->department->name }}</p>
                    <p class="mt-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Posisi</p>
                    <p class="mt-1 font-semibold text-slate-800">{{ $placement->position->name }}</p>
                    <div class="mt-3 inline-flex items-center rounded-full bg-sky-100 px-3 py-1 text-xs font-medium text-sky-700">
                        <i class="fa-solid fa-calendar-day mr-2"></i>
                        Sejak {{ $placement->start_date->format('d M Y') }}
                    </div>
                </div>
                @else
                <div class="rounded-xl border border-dashed border-sky-200 bg-white p-4 text-sm text-slate-400">
                    Belum pernah ditempatkan.
                </div>
                @endif

                <a href="{{ route('master.employees.placements.create', $employee->slug) }}" class="mt-auto inline-flex items-center gap-2 rounded-full bg-sky-900 px-4 py-2.5 text-sm font-medium text-white shadow-md transition duration-200 hover:-translate-y-0.5 hover:bg-sky-800">
                    <i class="fa fa-right-left text-sm"></i>
                    Tempatkan / Mutasi
                </a>
            </div>
        </div>

        {{-- Shift --}}
        <div class="flex flex-col rounded-xl border border-sky-200 bg-white p-6 shadow-md">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-900 text-white shadow-sm">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Jadwal Shift Saat Ini</h3>
                        <p class="text-sm text-slate-500">Informasi jadwal kerja terbaru</p>
                    </div>
                </div>

                <a href="{{ route('master.employees.shift-schedules.index', $employee->slug) }}" class="inline-flex items-center gap-2 rounded-full border border-sky-300 bg-sky-50 px-3 py-1.5 text-xs font-semibold text-sky-800 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-sky-100">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Lihat Riwayat
                </a>
            </div>

            @php $schedule = $employee->currentShiftSchedule(); @endphp

            <div class="mt-5 flex flex-1 flex-col">
                @if($schedule)
                <div class="rounded-xl border border-sky-200 bg-sky-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Shift</p>
                    <p class="mt-1 font-semibold text-slate-800">{{ $schedule->shift->name }} ({{ $schedule->shift->start_time }} - {{ $schedule->shift->end_time }})</p>
                    <div class="mt-3 inline-flex items-center rounded-full bg-white px-3 py-1 text-xs font-medium text-sky-700 shadow-sm">
                        <i class="fa-solid fa-calendar-day mr-2"></i>
                        Sejak {{ $schedule->start_date->format('d M Y') }}
                    </div>
                </div>
                @else
                <div class="rounded-xl border border-dashed border-sky-200 bg-sky-50 p-4 text-sm text-slate-400">
                    Belum ada jadwal shift.
                </div>
                @endif

                <a href="{{ route('master.employees.shift-schedules.create', $employee->slug) }}" class="mt-auto inline-flex items-center gap-2 rounded-full bg-sky-900 px-4 py-2.5 text-sm font-medium text-white shadow-md transition duration-200 hover:-translate-y-0.5 hover:bg-sky-800">
                    <i class="fa fa-clock text-sm"></i>
                    Jadwalkan Shift
                </a>
            </div>
        </div>

    </div>
</div>
@endsection