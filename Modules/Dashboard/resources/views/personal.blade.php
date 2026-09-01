@extends('shared::layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="flex items-center gap-2 mb-5">
        <i class="fas fa-calendar-check text-sky-800"></i>
        <h1 class="text-lg font-semibold text-sky-950">Halo, {{ $employee->name }}</h1>
    </div>

    {{-- Card Hari Ini --}}
    @php
    $status = $today['status'] ?? 'not_checked_in';
    $isCheckedIn = in_array($status, ['checked_in', 'checked_out']);
    $isCheckedOut = $status === 'checked_out';
    @endphp
    <div class="relative rounded-2xl p-5 mb-6 border {{ $isCheckedIn ? 'bg-gradient-to-br from-emerald-50 to-white border-emerald-200' : 'bg-gradient-to-br from-slate-50 to-white border-slate-200' }}">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-xs font-semibold tracking-wide {{ $isCheckedIn ? 'text-emerald-700' : 'text-slate-500' }}">
                    HARI INI · {{ now()->translatedFormat('l, d M') }}
                </div>
                <div class="text-lg font-bold text-slate-800 mt-0.5">
                    {{ $today['shift_name'] ?? 'Belum Ada Jadwal' }}
                </div>
            </div>
            <div class="w-11 h-11 rounded-full flex items-center justify-center {{ $isCheckedIn ? 'bg-emerald-500' : 'bg-slate-300' }}">
                <i class="fas {{ $isCheckedIn ? 'fa-check' : 'fa-hourglass-half' }} text-white text-lg"></i>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="bg-white rounded-xl p-3 border {{ $isCheckedIn ? 'border-emerald-100' : 'border-slate-100' }}">
                <div class="flex items-center gap-2 mb-1.5">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $isCheckedIn ? 'bg-emerald-100' : 'bg-slate-100' }}">
                        <i class="fas fa-right-to-bracket text-[10px] {{ $isCheckedIn ? 'text-emerald-700' : 'text-slate-400' }}"></i>
                    </div>
                    <span class="text-[10px] font-semibold text-slate-400 tracking-wide">CHECK-IN</span>
                </div>
                <div class="text-xl font-bold {{ $isCheckedIn ? 'text-slate-800' : 'text-slate-300' }}">
                    {{ $today['check_in_time'] ?? '--:--' }}
                </div>
            </div>

            <div class="bg-white rounded-xl p-3 border border-dashed {{ $isCheckedOut ? 'border-emerald-100' : 'border-slate-200' }}">
                <div class="flex items-center gap-2 mb-1.5">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $isCheckedOut ? 'bg-emerald-100' : 'bg-slate-100' }}">
                        <i class="fas fa-right-from-bracket text-[10px] {{ $isCheckedOut ? 'text-emerald-700' : 'text-slate-400' }}"></i>
                    </div>
                    <span class="text-[10px] font-semibold text-slate-400 tracking-wide">CHECK-OUT</span>
                </div>
                <div class="text-xl font-bold {{ $isCheckedOut ? 'text-slate-800' : 'text-slate-300' }}">
                    {{ $today['check_out_time'] ?? '--:--' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan Bulanan --}}
    <div class="text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wide">
        Ringkasan {{ $monthLabel }}
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="rounded-xl border border-slate-100 p-4 text-center">
            <div class="text-2xl font-bold text-emerald-600">{{ $monthSummary['hadir'] }}</div>
            <div class="text-xs text-slate-400 mt-1">Hadir</div>
        </div>
        <div class="rounded-xl border border-slate-100 p-4 text-center">
            <div class="text-2xl font-bold text-amber-600">{{ $monthSummary['terlambat'] }}</div>
            <div class="text-xs text-slate-400 mt-1">Terlambat</div>
        </div>
        <div class="rounded-xl border border-slate-100 p-4 text-center">
            <div class="text-2xl font-bold text-violet-600">{{ $monthSummary['cuti'] }}</div>
            <div class="text-xs text-slate-400 mt-1">Cuti</div>
        </div>
        <div class="rounded-xl border border-slate-100 p-4 text-center">
            <div class="text-2xl font-bold text-rose-600">{{ $monthSummary['absen'] }}</div>
            <div class="text-xs text-slate-400 mt-1">Absen</div>
        </div>
    </div>
</div>
@endsection