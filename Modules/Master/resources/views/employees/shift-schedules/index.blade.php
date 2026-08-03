@extends('shared::layouts.app')

@section('title', 'Riwayat Jadwal Shift')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('master.employees.show', $employee->slug) }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-blue-100 bg-blue-900 hover:bg-blue-800 transition duration-200 translate-x-0 hover:-translate-x-1">
                <i class="fa fa-arrow-left text-md text-blue-50"></i>
            </a>
            <div>
                <h1 class="text-xl font-semibold text-blue-800">Riwayat Jadwal Shift</h1>
                <p class="text-sm text-slate-500">{{ $employee->name }}</p>
            </div>
        </div>
        <a href="{{ route('master.employees.shift-schedules.create', $employee->slug) }}" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-full text-sm font-medium transition duration-200">
            <i class="fa fa-plus text-sm"></i> Jadwalkan Shift
        </a>
    </div>

    <div class="mt-6 bg-white shadow-md p-4">
        <div class="border border-gray-200 rounded-md grid grid-cols-4 gap-4 p-4 text-gray-700 font-semibold text-sm">
            <span>Shift</span>
            <span>Mulai</span>
            <span>Berakhir</span>
            <span>Catatan</span>
        </div>

        @forelse($history as $schedule)
        <div class="border border-gray-200 rounded-md grid grid-cols-4 gap-4 p-4 items-center text-gray-700 text-sm">
            <span>{{ $schedule->shift->name }} ({{ $schedule->shift->start_time }} - {{ $schedule->shift->end_time }})</span>
            <span>{{ $schedule->start_date->format('d M Y') }}</span>
            <span>{{ $schedule->end_date?->format('d M Y') ?? '—' }}</span>
            <span class="text-slate-500">{{ $schedule->notes ?? '-' }}</span>
        </div>
        @empty
        <div class="border border-gray-200 rounded-md p-10 text-center text-sm text-slate-400">Belum ada riwayat jadwal.</div>
        @endforelse
    </div>
</div>
@endsection