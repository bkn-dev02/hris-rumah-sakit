@extends('shared::layouts.app')

@section('title', 'Approval Cuti — HRD')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-8">
    <div class="mb-6 rounded-xl bg-sky-100 px-5 py-4 shadow-sm">
        <h1 class="text-xl font-bold text-sky-950">Approval Cuti (HRD)</h1>
        <p class="mt-0.5 text-xs text-slate-500">Pengajuan cuti yang sudah disetujui atasan, menunggu keputusan final HRD</p>
    </div>

    @if (session('success'))
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
        {{ session('error') }}
    </div>
    @endif

    <div class="space-y-3">
        @forelse ($leaveRequests as $leaveRequest)
        <div class="rounded-xl border border-sky-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-semibold text-sky-950">{{ $leaveRequest->employee->name }}</p>
                    <p class="text-sm text-slate-600">{{ $leaveRequest->leaveType->name }}</p>
                    <p class="text-xs text-slate-400">
                        {{ $leaveRequest->start_date->format('d M Y') }} - {{ $leaveRequest->end_date->format('d M Y') }}
                        ({{ $leaveRequest->total_days }} hari)
                    </p>
                    <p class="mt-1 text-sm text-slate-700">{{ $leaveRequest->reason }}</p>
                    @if ($leaveRequest->supervisor)
                    <p class="mt-1 text-xs text-slate-400">
                        Disetujui atasan: {{ $leaveRequest->supervisor->name }}
                        @if ($leaveRequest->supervisor_note) — "{{ $leaveRequest->supervisor_note }}" @endif
                    </p>
                    @endif
                </div>

                <div class="flex gap-2">
                    <form action="{{ route('leave.decide-hr', $leaveRequest->id) }}" method="POST" onsubmit="return confirm('Setujui pengajuan ini?');">
                        @csrf
                        <input type="hidden" name="decision" value="approve">
                        <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                            <i class="fa-solid fa-check"></i> Setujui
                        </button>
                    </form>
                    <form action="{{ route('leave.decide-hr', $leaveRequest->id) }}" method="POST" onsubmit="return confirm('Tolak pengajuan ini?');">
                        @csrf
                        <input type="hidden" name="decision" value="reject">
                        <button type="submit" class="rounded-lg bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-700">
                            <i class="fa-solid fa-xmark"></i> Tolak
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="rounded-xl border border-sky-200 bg-white p-10 text-center text-sm text-slate-500 shadow-sm">
            Tidak ada pengajuan cuti yang menunggu keputusan HRD.
        </div>
        @endforelse
    </div>
</div>
@endsection