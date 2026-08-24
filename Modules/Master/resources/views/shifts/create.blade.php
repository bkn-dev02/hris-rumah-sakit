@extends('shared::layouts.app')

@section('title', isset($shift) ? 'Ubah Shift' : 'Tambah Shift')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('master.shifts.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-sky-100 bg-sky-900 hover:bg-sky-800 transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-sky-50"></i>
        </a>
        <h1 class="text-xl font-semibold text-sky-800">{{ isset($shift) ? 'Ubah ' . $shift->name . ' (Versi Baru)' : 'Tambah Shift' }}</h1>
    </div>

    @if(isset($shift))
    <div class="mt-2 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
        Perubahan ini akan disimpan sebagai versi baru. Data jam kerja lama tetap tersimpan sebagai riwayat.
    </div>
    @endif

    <form method="POST" action="{{ route('master.shifts.store') }}" class="mt-6 rounded-lg border border-sky-200 bg-white p-6">
        @csrf

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-sky-700">Kode Shift</label>
                <input type="text" name="code" value="{{ old('code', $shift->code ?? '') }}"
                    {{ isset($shift) ? 'readonly' : '' }}
                    class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 {{ isset($shift) ? 'bg-sky-50 text-sky-500' : '' }}">
                <p class="mt-1 text-xs text-sky-400">Kode ini yang menjadi identitas shift (mis. PAGI, SIANG, MALAM), dipakai untuk melacak seluruh riwayat versinya.</p>
                @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-sky-700">Nama Shift</label>
                <input type="text" name="name" value="{{ old('name', $shift->name ?? '') }}"
                    class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-sky-700">Jam Mulai</label>
                <input type="time" name="start_time" value="{{ old('start_time', $shift->start_time ?? '') }}"
                    class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                @error('start_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-sky-700">Jam Selesai</label>
                <input type="time" name="end_time" value="{{ old('end_time', $shift->end_time ?? '') }}"
                    class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                @error('end_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-medium text-sky-700">Berlaku Efektif Mulai</label>
                <input type="date" name="effective_date" value="{{ old('effective_date', now()->toDateString()) }}"
                    class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                @error('effective_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('master.shifts.index') }}" class="rounded-lg border border-sky-200 px-4 py-2 text-sm font-medium text-sky-600 hover:bg-sky-50">Batal</a>
            <x-shared::button type="submit" variant="primary">Simpan</x-shared::button>
        </div>
    </form>
</div>
@endsection