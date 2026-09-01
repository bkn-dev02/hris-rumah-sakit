@extends('shared::layouts.app')

@section('title', isset($shift) ? 'Ubah Shift' : 'Tambah Shift')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('master.shifts.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-[#dfeee1] bg-[#173f34] hover:bg-[#173f34] transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-[#edf5ee]"></i>
        </a>
        <h1 class="text-xl font-semibold text-[#1f4d3d]">{{ isset($shift) ? 'Ubah ' . $shift->name . ' (Versi Baru)' : 'Tambah Shift' }}</h1>
    </div>

    @if(isset($shift))
    <div class="mt-2 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
        Perubahan ini akan disimpan sebagai versi baru. Data jam kerja lama tetap tersimpan sebagai riwayat.
    </div>
    @endif

    <form method="POST" action="{{ route('master.shifts.store') }}" class="mt-6 rounded-lg border border-[#dfeee1] bg-white p-6">
        @csrf

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-[#2a684f]">Kode Shift</label>
                <input type="text" name="code" value="{{ old('code', $shift->code ?? '') }}"
                    {{ isset($shift) ? 'readonly' : '' }}
                    class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1] {{ isset($shift) ? 'bg-[#f8fbf8] text-[#2a684f]' : '' }}">
                <p class="mt-1 text-xs text-[#2a684f]">Kode ini yang menjadi identitas shift (mis. PAGI, SIANG, MALAM), dipakai untuk melacak seluruh riwayat versinya.</p>
                @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-[#2a684f]">Nama Shift</label>
                <input type="text" name="name" value="{{ old('name', $shift->name ?? '') }}"
                    class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-[#2a684f]">Jam Mulai</label>
                <input type="time" name="start_time" value="{{ old('start_time', $shift->start_time ?? '') }}"
                    class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
                @error('start_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-[#2a684f]">Jam Selesai</label>
                <input type="time" name="end_time" value="{{ old('end_time', $shift->end_time ?? '') }}"
                    class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
                @error('end_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-medium text-[#2a684f]">Berlaku Efektif Mulai</label>
                <input type="date" name="effective_date" value="{{ old('effective_date', now()->toDateString()) }}"
                    class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
                @error('effective_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('master.shifts.index') }}" class="rounded-lg border border-[#dfeee1] px-4 py-2 text-sm font-medium text-[#2a684f] hover:bg-[#f8fbf8]">Batal</a>
            <x-shared::button type="submit" variant="primary">Simpan</x-shared::button>
        </div>
    </form>
</div>
@endsection
