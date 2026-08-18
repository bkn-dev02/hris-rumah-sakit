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

    @if (session('success'))
    <div class="mb-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('success') }}
    </div>
    @endif

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
            <p class="text-sm font-semibold text-sky-950">{{ $leaveRequest->statusLabel() }}</p>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-sky-200 bg-white p-6 shadow-sm">
        <h2 class="mb-4 text-sm font-semibold text-sky-950">Riwayat Persetujuan</h2>
        <ol class="space-y-4">
            @foreach ($leaveRequest->approvals as $approval)
            @php
            $stepColor = match ($approval->status) {
            'approved' => 'emerald',
            'rejected' => 'rose',
            default => 'amber',
            };
            @endphp
            <li class="flex items-start gap-3">
                <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-{{ $stepColor }}-100 text-{{ $stepColor }}-700 text-xs font-bold">
                    {{ $approval->sequence }}
                </span>
                <div>
                    <p class="text-sm font-medium text-slate-700">
                        {{ $approval->approver->name }}
                        <span class="text-xs text-slate-400">({{ $approval->typeLabel() }})</span>
                    </p>
                    <p class="text-xs text-{{ $stepColor }}-700">
                        @if ($approval->status === 'pending')
                        Menunggu persetujuan
                        @else
                        {{ $approval->status === 'approved' ? 'Disetujui' : 'Ditolak' }}
                        @if($approval->decided_at) — {{ $approval->decided_at->format('d M Y H:i') }} @endif
                        @endif
                    </p>
                    @if ($approval->note)
                    <p class="mt-1 text-xs italic text-slate-500">"{{ $approval->note }}"</p>
                    @endif
                </div>
            </li>
            @endforeach
        </ol>
    </div>

    @if ($canDecide)
    <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow-sm">
        <h2 class="mb-3 text-sm font-semibold text-amber-800">Giliran Anda untuk memutuskan</h2>
        <form method="POST" action="{{ route('leave.decide', $leaveRequest) }}" class="space-y-3">
            @csrf
            <textarea name="note" rows="2" placeholder="Catatan (opsional)"
                class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500"></textarea>
            <div class="flex gap-2">
                <button type="submit" name="decision" value="approve"
                    class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                    Setujui
                </button>
                <button type="submit" name="decision" value="reject"
                    class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-medium text-white hover:bg-rose-700">
                    Tolak
                </button>
            </div>
        </form>
    </div>
    @endif
</div>
@endsection