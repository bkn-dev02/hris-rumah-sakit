@extends('shared::layouts.app')

@section('title', $employee->name)

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('success') }}
    </div>
    @endif

    <div class="flex items-center gap-4">
        <a href="{{ route('master.employees.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-blue-100 bg-blue-900 hover:bg-blue-800 transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-blue-50"></i>
        </a>
        <h1 class="text-xl font-semibold text-blue-800">Detail Employee</h1>
    </div>

    {{-- Bio card --}}
    <div class="mt-6 bg-white shadow-md p-6">
        <div class="flex items-center gap-4">
            <x-shared::avatar
                :src="$employee->photo ? asset('storage/' . $employee->photo) : null"
                :name="$employee->name"
                size="xl" />
            <div>
                <h2 class="text-lg font-semibold text-slate-800">{{ $employee->name }}</h2>
                <p class="text-sm text-slate-500">{{ $employee->employee_number }} &middot; {{ $employee->employmentStatus->name ?? '-' }}</p>
                <div class="mt-1">
                    @if($employee->is_active)
                    <x-shared::badge variant="success" dot>Aktif</x-shared::badge>
                    @else
                    <x-shared::badge variant="secondary" dot>Nonaktif</x-shared::badge>
                    @endif
                </div>
            </div>
            <a href="{{ route('master.employees.edit', $employee->slug) }}" class="ml-auto bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-full text-sm font-medium transition duration-200">
                <i class="fa fa-pen text-sm"></i> Edit Data
            </a>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-4 border-t border-slate-100 pt-6 sm:grid-cols-3">
            <div>
                <p class="text-xs text-slate-400">Telepon</p>
                <p class="text-sm text-slate-700">{{ $employee->phone ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Tanggal Bergabung</p>
                <p class="text-sm text-slate-700">{{ $employee->hire_date->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Penempatan --}}
        <div class="bg-white shadow-md p-6">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-slate-800">Penempatan Saat Ini</h3>
                <a href="{{ route('master.employees.placements.index', $employee->slug) }}" class="text-xs text-blue-600 hover:underline">Lihat Riwayat</a>
            </div>

            @php $placement = $employee->currentPlacement(); @endphp

            @if($placement)
            <div class="mt-3 space-y-1">
                <p class="text-sm text-slate-500">Department</p>
                <p class="font-medium text-slate-800">{{ $placement->department->name }}</p>
                <p class="mt-2 text-sm text-slate-500">Posisi</p>
                <p class="font-medium text-slate-800">{{ $placement->position->name }}</p>
                <p class="mt-2 text-xs text-slate-400">Sejak {{ $placement->start_date->format('d M Y') }}</p>
            </div>
            @else
            <p class="mt-3 text-sm text-slate-400">Belum pernah ditempatkan.</p>
            @endif

            <a href="{{ route('master.employees.placements.create', $employee->slug) }}" class="mt-4 inline-block bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-full text-sm font-medium transition duration-200">
                <i class="fa fa-right-left text-sm"></i> Tempatkan / Mutasi
            </a>
        </div>

        {{-- Shift --}}
        <div class="bg-white shadow-md p-6">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-slate-800">Jadwal Shift Saat Ini</h3>
                <a href="{{ route('master.employees.shift-schedules.index', $employee->slug) }}" class="text-xs text-blue-600 hover:underline">Lihat Riwayat</a>
            </div>

            @php $schedule = $employee->currentShiftSchedule(); @endphp

            @if($schedule)
            <div class="mt-3 space-y-1">
                <p class="text-sm text-slate-500">Shift</p>
                <p class="font-medium text-slate-800">{{ $schedule->shift->name }} ({{ $schedule->shift->start_time }} - {{ $schedule->shift->end_time }})</p>
                <p class="mt-2 text-xs text-slate-400">Sejak {{ $schedule->start_date->format('d M Y') }}</p>
            </div>
            @else
            <p class="mt-3 text-sm text-slate-400">Belum ada jadwal shift.</p>
            @endif

            <a href="{{ route('master.employees.shift-schedules.create', $employee->slug) }}" class="mt-4 inline-block bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-full text-sm font-medium transition duration-200">
                <i class="fa fa-clock text-sm"></i> Jadwalkan Shift
            </a>
        </div>

    </div>
</div>
@endsection