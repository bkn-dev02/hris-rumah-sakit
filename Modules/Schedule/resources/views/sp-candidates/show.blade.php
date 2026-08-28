@extends('shared::layouts.app')

@section('title', 'Detail SP Candidate')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <a href="{{ route('schedule.sp-candidates.index') }}" class="text-sm text-slate-500 hover:text-slate-700 flex items-center gap-2 mb-4">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    @if (session('success'))
    <div class="bg-emerald-50 text-emerald-700 text-sm rounded-xl px-4 py-3 mb-4">{{ session('success') }}</div>
    @endif

    {{-- Info utama --}}
    <div class="bg-white rounded-2xl shadow-md p-5 mb-4">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-full bg-slate-100 flex items-center justify-center text-sm font-semibold text-slate-600">
                    {{ collect(explode(' ', $candidate->employee->name))->map(fn($w) => $w[0] ?? '')->take(2)->implode('') }}
                </div>
                <div>
                    <div class="font-semibold text-slate-800">{{ $candidate->employee->name }}</div>
                    <div class="text-xs text-slate-400">{{ $candidate->department->name }}</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <div class="text-xs text-slate-400">Tanggal</div>
                <div class="text-slate-700">{{ $candidate->date->translatedFormat('d F Y') }}</div>
            </div>
            <div>
                <div class="text-xs text-slate-400">Shift</div>
                <div class="text-slate-700">{{ $candidate->shift->name }}</div>
            </div>
            <div>
                <div class="text-xs text-slate-400">Terdeteksi</div>
                <div class="text-slate-700">{{ $candidate->detected_at->translatedFormat('d M, H:i') }}</div>
            </div>
            @if ($candidate->late_checkin_at)
            <div>
                <div class="text-xs text-slate-400">Check-in Terlambat</div>
                <div class="text-slate-700">{{ $candidate->late_checkin_at->translatedFormat('d M, H:i') }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Aksi: Decide (kalau pending_decision) --}}
    @if ($candidate->status === 'pending_decision')
    <div class="bg-white rounded-2xl shadow-md p-5 mb-4">
        <div class="text-sm font-medium text-slate-600 mb-3">Keputusan HRD</div>
        <div class="flex gap-2">
            <form method="POST" action="{{ route('schedule.sp-candidates.decide', $candidate->id) }}" class="flex-1">
                @csrf
                <input type="hidden" name="issue_sp" value="1">
                <button class="w-full py-2 rounded-lg bg-rose-50 text-rose-700 text-sm font-medium hover:-translate-y-0.5 transition">
                    Tetap Terbitkan SP
                </button>
            </form>
            <form method="POST" action="{{ route('schedule.sp-candidates.decide', $candidate->id) }}" class="flex-1">
                @csrf
                <input type="hidden" name="issue_sp" value="0">
                <button class="w-full py-2 rounded-lg bg-slate-100 text-slate-600 text-sm font-medium hover:-translate-y-0.5 transition">
                    Batalkan
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Preview Surat SP (kalau sudah diterbitkan) --}}
    @if ($candidate->status === 'resolved_issued' && $candidate->spLetter)
    @php
    $filePath = $candidate->spLetter->file_path;
    $fileUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($filePath);
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $isPdf = $extension === 'pdf';
    @endphp
    <div class="bg-white rounded-2xl shadow-md p-5 mb-4">
        <div class="flex items-center justify-between mb-3">
            <div class="text-sm font-medium text-slate-600">
                Surat SP ke-{{ $candidate->spLetter->sp_number }}
            </div>
            <a href="{{ $fileUrl }}" target="_blank" class="text-xs text-sky-700 hover:underline flex items-center gap-1">
                <i class="fas fa-arrow-up-right-from-square"></i> Buka di tab baru
            </a>
        </div>

        @if ($isPdf)
        <iframe src="{{ $fileUrl }}" class="w-full rounded-xl border border-slate-100" style="height: 500px;"></iframe>
        @else
        <img src="{{ $fileUrl }}" alt="Surat SP" class="w-full rounded-xl border border-slate-100 max-h-[500px] object-contain bg-slate-50">
        @endif

        <div class="text-xs text-slate-400 mt-2">
            Diterbitkan pada {{ $candidate->spLetter->issued_at->translatedFormat('d F Y, H:i') }}
        </div>
    </div>
    @endif

    {{-- Aksi: Issue SP (kalau status candidate) --}}
    @if ($candidate->status === 'candidate')
    <div class="bg-white rounded-2xl shadow-md p-5 mb-4">
        <div class="text-sm font-medium text-slate-600 mb-3">Terbitkan Surat SP</div>
        <form method="POST" action="{{ route('schedule.sp-candidates.issue', $candidate->id) }}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required
                class="text-sm w-full mb-3 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-sky-50 file:text-sky-700">
            <button class="px-4 py-2 rounded-lg bg-sky-800 text-white text-sm font-medium hover:-translate-y-0.5 transition">
                Terbitkan SP
            </button>
        </form>
    </div>
    @endif

    {{-- Aksi: Konfirmasi Manual (kalau belum resolved) --}}
    @if (!in_array($candidate->status, ['resolved_issued', 'cancelled_manual', 'cancelled_late_checkin_decision']))
    <div class="bg-white rounded-2xl shadow-md p-5">
        <div class="text-sm font-medium text-slate-600 mb-3">Konfirmasi Manual</div>
        <form method="POST" action="{{ route('schedule.sp-candidates.confirm', $candidate->id) }}">
            @csrf
            <textarea name="note" required placeholder="Tambahkan keterangan..."
                class="w-full text-sm rounded-lg border-slate-200 mb-3" rows="3"></textarea>
            <button class="px-4 py-2 rounded-lg bg-sky-800 text-white text-sm font-medium hover:-translate-y-0.5 transition">
                Simpan Konfirmasi
            </button>
        </form>
    </div>
    @endif
</div>
@endsection