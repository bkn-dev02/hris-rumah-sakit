@extends('shared::layouts.app')

@section('title', 'Detail Pengajuan Cuti')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-8">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('leave.index') }}"
            class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-900 text-white hover:bg-sky-950 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-xl font-bold text-sky-950">Detail Pengajuan Cuti</h1>
    </div>

    <div class="rounded-2xl border border-sky-200 bg-white p-6 shadow-sm space-y-4">
        <div>
            <p class="text-xs text-slate-400">Karyawan</p>
            <p class="text-sm font-semibold text-sky-950">{{ $leaveRequest->employee->name }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-400">Jenis Cuti</p>
            <p class="text-sm text-slate-700">{{ $leaveRequest->leaveType->name }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-400">Tanggal</p>
            <p class="text-sm text-slate-700">
                {{ $leaveRequest->start_date->format('d M Y') }} - {{ $leaveRequest->end_date->format('d M Y') }}
                ({{ $leaveRequest->total_days }} hari kerja)
            </p>
        </div>
        <div>
            <p class="text-xs text-slate-400">Alasan</p>
            <p class="text-sm text-slate-700">{{ $leaveRequest->reason }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-400">Status</p>
            <p class="text-sm text-slate-700">{{ $leaveRequest->status }}</p>
        </div>

        @if ($leaveRequest->supervisor)
        <div>
            <p class="text-xs text-slate-400">Atasan</p>
            <p class="text-sm text-slate-700">
                {{ $leaveRequest->supervisor->name }}
                @if ($leaveRequest->supervisor_note) — "{{ $leaveRequest->supervisor_note }}" @endif
            </p>
        </div>
        @endif

        @if ($leaveRequest->hrApprover)
        <div>
            <p class="text-xs text-slate-400">HRD</p>
            <p class="text-sm text-slate-700">
                {{ $leaveRequest->hrApprover->name }}
                @if ($leaveRequest->hr_note) — "{{ $leaveRequest->hr_note }}" @endif
            </p>
        </div>
        @endif
    </div>
</div>
@endsection