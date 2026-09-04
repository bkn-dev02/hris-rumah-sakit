@extends('shared::layouts.app')

@section('title', 'Jadwal Saya')

@section('content')
<div class="mx-auto max-w-5xl px-3 py-4 sm:px-6 sm:py-8">
    <div class="flex flex-col gap-4 rounded-t-2xl bg-gradient-to-r from-[#173f34] to-[#2a684f] px-5 py-4 shadow-md sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div class="flex items-center gap-3">
            <i class="fas fa-calendar-days text-[#dfeee1]"></i>
            <div>
                <h1 class="font-semibold text-white">Jadwal Saya</h1>
                <p class="mt-1 text-xs text-[#dfeee1]">{{ $employee->name }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between gap-3 sm:justify-end">
            <a href="{{ route('schedule.monthly-grid.personal', ['year' => $monthDate->copy()->subMonth()->year, 'month' => $monthDate->copy()->subMonth()->month]) }}" class="text-white/80 transition hover:text-white" aria-label="Bulan sebelumnya">
                <i class="fas fa-chevron-left"></i>
            </a>
            <span class="min-w-[130px] text-center text-sm text-white">{{ $monthDate->translatedFormat('F Y') }}</span>
            <a href="{{ route('schedule.monthly-grid.personal', ['year' => $monthDate->copy()->addMonth()->year, 'month' => $monthDate->copy()->addMonth()->month]) }}" class="text-white/80 transition hover:text-white" aria-label="Bulan berikutnya">
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>

    <div class="overflow-x-auto rounded-b-2xl bg-white shadow-md">
        <div class="grid grid-cols-1 gap-3 p-4 sm:grid-cols-2">
            @forelse ($schedule as $entry)
            <div class="flex items-center justify-between rounded-xl border border-slate-100 px-4 py-3 {{ \Carbon\Carbon::parse($entry['date'])->isWeekend() ? 'bg-slate-50' : 'bg-white' }}">
                <div>
                    <p class="text-sm font-semibold text-slate-700">{{ \Carbon\Carbon::parse($entry['date'])->translatedFormat('l, d F') }}</p>
                    @if ($entry['is_libur'])
                    <p class="mt-1 text-xs font-medium text-slate-500">Hari libur</p>
                    @elseif ($entry['shift'])
                    <p class="mt-1 text-xs text-[#567564]">{{ $entry['shift']['name'] }} &middot; {{ $entry['shift']['start_time'] }} - {{ $entry['shift']['end_time'] }}</p>
                    @else
                    <p class="mt-1 text-xs text-slate-400">Belum diatur</p>
                    @endif
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $entry['is_libur'] ? 'bg-slate-100 text-slate-500' : ($entry['shift'] ? 'bg-[#edf5ee] text-[#1f4d3d]' : 'bg-slate-50 text-slate-300') }}">
                    <i class="fas {{ $entry['is_libur'] ? 'fa-mug-hot' : ($entry['shift'] ? 'fa-clock' : 'fa-minus') }}"></i>
                </div>
            </div>
            @empty
            <div class="col-span-full p-10 text-center text-sm text-slate-400">Belum ada jadwal untuk bulan ini.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection