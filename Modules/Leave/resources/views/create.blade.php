@extends('shared::layouts.app')

@section('title', 'Ajukan Cuti')

@section('content')
<div class="mx-auto max-w-3xl px-3 py-4 sm:px-6 sm:py-8">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('leave.index') }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#1f4d3d] text-white shadow-sm transition hover:bg-[#173f34]" aria-label="Kembali">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-[#173f34]">Ajukan Cuti</h1>
            <p class="mt-0.5 text-xs text-[#2a684f]">Lengkapi data pengajuan cuti Anda</p>
        </div>
    </div>

    @if (session('error'))
    <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ session('error') }}</div>
    @endif

    <form action="{{ route('leave.requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5 rounded-2xl border border-[#dfeee1] bg-white p-5 shadow-sm sm:p-6">
        @csrf
        <div>
            <label for="leave_type_id" class="mb-1.5 block text-sm font-semibold text-[#173f34]">Jenis Cuti</label>
            <select id="leave_type_id" name="leave_type_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-[#2a684f] focus:ring-[#dfeee1]">
                <option value="">Pilih jenis cuti</option>
                @foreach ($leaveTypes as $leaveType)
                <option value="{{ $leaveType->id }}" @selected(old('leave_type_id')==$leaveType->id)>
                    {{ $leaveType->name }}{{ $leaveType->requires_quota ? ' (Sisa kuota: ' . ($leaveType->remaining_quota ?? 0) . ' hari)' : '' }}
                </option>
                @endforeach
            </select>
            @error('leave_type_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="start_date" class="mb-1.5 block text-sm font-semibold text-[#173f34]">Tanggal Mulai</label>
                <input id="start_date" type="date" name="start_date" min="{{ now()->toDateString() }}" value="{{ old('start_date') }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-[#2a684f] focus:ring-[#dfeee1]">
                @error('start_date') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="end_date" class="mb-1.5 block text-sm font-semibold text-[#173f34]">Tanggal Selesai</label>
                <input id="end_date" type="date" name="end_date" min="{{ now()->toDateString() }}" value="{{ old('end_date') }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-[#2a684f] focus:ring-[#dfeee1]">
                @error('end_date') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="reason" class="mb-1.5 block text-sm font-semibold text-[#173f34]">Alasan</label>
            <textarea id="reason" name="reason" rows="4" maxlength="1000" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-[#2a684f] focus:ring-[#dfeee1]" placeholder="Tuliskan alasan pengajuan cuti">{{ old('reason') }}</textarea>
            @error('reason') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="attachment" class="mb-1.5 block text-sm font-semibold text-[#173f34]">Lampiran</label>
            <input id="attachment" type="file" name="attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-600 file:mr-3 file:rounded-md file:border-0 file:bg-[#edf5ee] file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-[#1f4d3d]">
            <p class="mt-1 text-xs text-slate-400">Opsional, maksimal 2 MB.</p>
            @error('attachment') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end border-t border-slate-100 pt-5">
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#1f4d3d] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#173f34]">
                <i class="fa-solid fa-paper-plane text-xs"></i>
                Kirim Pengajuan
            </button>
        </div>
    </form>
</div>
@endsection