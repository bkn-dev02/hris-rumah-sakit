@extends('shared::layouts.app')

@section('title', 'Detail Pengajuan')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    @if(session('error'))
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="flex items-center gap-4">
        <a href="{{ route('attendance.exception-requests.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-sky-100 bg-sky-900 hover:bg-sky-800 transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-sky-50"></i>
        </a>
        <h1 class="text-xl font-semibold text-sky-800">Detail Pengajuan</h1>
    </div>

    <div class="mt-6 bg-white shadow-md p-6">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <p class="text-xs text-slate-400">Pegawai</p>
                <p class="text-sm font-medium text-slate-800">{{ $exceptionRequest->employee->name }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Tanggal</p>
                <p class="text-sm font-medium text-slate-800">{{ $exceptionRequest->work_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Jenis Pengajuan</p>
                <p class="text-sm font-medium text-slate-800">{{ $exceptionRequest->status->name }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Status</p>
                @php
                $approvalVariant = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$exceptionRequest->approval_status];
                @endphp
                <x-shared::badge :variant="$approvalVariant">{{ ucfirst($exceptionRequest->approval_status) }}</x-shared::badge>
            </div>
            <div class="sm:col-span-2">
                <p class="text-xs text-slate-400">Alasan</p>
                <p class="text-sm text-slate-700">{{ $exceptionRequest->reason }}</p>
            </div>
            @if($exceptionRequest->attachment)
            <div class="sm:col-span-2">
                <p class="text-xs text-slate-400">Lampiran</p>
                <a href="{{ asset('storage/' . $exceptionRequest->attachment) }}" target="_blank" class="text-sm text-sky-600 hover:underline">Lihat Lampiran</a>
            </div>
            @endif
            @if($exceptionRequest->approval_status !== 'pending')
            <div>
                <p class="text-xs text-slate-400">Diproses oleh</p>
                <p class="text-sm text-slate-700">{{ $exceptionRequest->approvedBy->username ?? '-' }} &middot; {{ $exceptionRequest->approved_at?->format('d M Y H:i') }}</p>
            </div>
            @if($exceptionRequest->rejection_reason)
            <div>
                <p class="text-xs text-slate-400">Alasan Penolakan</p>
                <p class="text-sm text-slate-700">{{ $exceptionRequest->rejection_reason }}</p>
            </div>
            @endif
            @endif
        </div>

        @if($exceptionRequest->isPending())
        <div class="mt-6 flex gap-3 border-t border-slate-100 pt-6">
            <form method="POST" action="{{ route('attendance.exception-requests.approve', $exceptionRequest->id) }}">
                @csrf
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white px-5 py-2 rounded-full text-sm font-medium transition duration-200">
                    <i class="fa fa-check text-sm"></i> Setujui
                </button>
            </form>

            <button type="button" onclick="document.getElementById('reject-form').classList.toggle('hidden')" class="bg-red-900 hover:bg-red-800 text-white px-5 py-2 rounded-full text-sm font-medium transition duration-200">
                <i class="fa fa-times text-sm"></i> Tolak
            </button>
        </div>

        <form id="reject-form" method="POST" action="{{ route('attendance.exception-requests.reject', $exceptionRequest->id) }}" class="mt-4 hidden">
            @csrf
            <label class="mb-1 block text-sm font-medium text-slate-700">Alasan Penolakan</label>
            <textarea name="rejection_reason" rows="2" class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">{{ old('rejection_reason') }}</textarea>
            @error('rejection_reason') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            <button type="submit" class="mt-2 bg-red-900 hover:bg-red-800 text-white px-4 py-2 rounded-full text-sm font-medium transition duration-200">Kirim Penolakan</button>
        </form>
        @endif
    </div>

</div>
@endsection