@extends('shared::layouts.app')

@section('title', 'Manajemen Cuti')

@section('content')
<div class="mx-auto max-w-5xl px-3 py-4 sm:px-6 sm:py-8">
    @if (session('success'))
    <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif

    <div class="mb-6 flex flex-col gap-4 rounded-xl bg-[#edf5ee] px-5 py-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[#173f34] shadow-sm">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-[#173f34]">Manajemen Cuti</h1>
                <p class="mt-0.5 text-xs text-slate-500">Riwayat pengajuan cuti {{ $employee->name }}</p>
            </div>
        </div>
        <a href="{{ route('leave.requests.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#1f4d3d] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#173f34]">
            <i class="fa-solid fa-file-signature text-xs"></i>
            Ajukan Cuti
        </a>
    </div>

    <div class="space-y-3">
        @forelse ($leaveRequests as $leaveRequest)
        <a href="{{ route('leave.show', $leaveRequest) }}" class="block rounded-xl border border-[#dfeee1] bg-white p-5 shadow-sm transition hover:border-[#bfe2c7] hover:bg-[#f8fbf8]">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-semibold text-[#173f34]">{{ $leaveRequest->leaveType?->name ?? 'Jenis cuti' }}</p>
                    <p class="mt-1 text-sm text-slate-600">{{ $leaveRequest->start_date->format('d M Y') }} - {{ $leaveRequest->end_date->format('d M Y') }}</p>
                    <p class="mt-1 text-xs text-slate-400">{{ $leaveRequest->total_days }} hari kerja</p>
                </div>
                <span class="inline-flex w-fit items-center rounded-full px-3 py-1 text-xs font-semibold
                    {{ $leaveRequest->status === 'approved' ? 'bg-emerald-50 text-emerald-700' : ($leaveRequest->status === 'rejected' ? 'bg-rose-50 text-rose-700' : ($leaveRequest->status === 'cancelled' ? 'bg-slate-100 text-slate-500' : 'bg-amber-50 text-amber-700')) }}">
                    {{ $leaveRequest->statusLabel() }}
                </span>
            </div>
        </a>
        @empty
        <div class="rounded-xl border border-[#dfeee1] bg-white p-10 text-center text-sm text-slate-500 shadow-sm">
            Belum ada riwayat pengajuan cuti.
        </div>
        @endforelse
    </div>
</div>
@endsection