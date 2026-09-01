@extends('shared::layouts.app')

@section('title', 'Jadwalkan Shift')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('master.employees.shift-schedules.index', $employee->slug) }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-[#dfeee1] bg-[#1f4d3d] hover:bg-[#173f34] transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-[#edf5ee]"></i>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-[#1f4d3d]">Jadwalkan Shift</h1>
            <p class="text-sm text-[#2a684f]">{{ $employee->name }}</p>
        </div>
    </div>

    @if($currentSchedule)
    <div class="mt-4 rounded-lg border border-[#dfeee1] bg-[#edf5ee] px-4 py-3 text-sm text-[#1f4d3d]">
        Saat ini shift <strong>{{ $currentSchedule->shift->name }}</strong>. Menyimpan jadwal baru akan otomatis mengakhiri jadwal ini.
    </div>
    @endif

    <form method="POST" action="{{ route('master.employees.shift-schedules.store', $employee->slug) }}" class="mt-6 bg-white shadow-md p-6 rounded-xl border border-[#dfeee1]">
        @csrf

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-[#1f4d3d]">Shift</label>
                <select name="shift_id" class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
                    <option value="">-- Pilih Shift --</option>
                    @foreach($shifts as $shift)
                    <option value="{{ $shift->id }}" @selected(old('shift_id')==$shift->id)>{{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})</option>
                    @endforeach
                </select>
                @error('shift_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-[#1f4d3d]">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ old('start_date', now()->toDateString()) }}"
                    class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
                @error('start_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-medium text-[#1f4d3d]">Catatan</label>
                <textarea name="notes" rows="2" class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">{{ old('notes') }}</textarea>
                @error('notes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('master.employees.shift-schedules.index', $employee->slug) }}" class="rounded-full border border-[#dfeee1] px-4 py-2 text-sm font-medium text-[#1f4d3d] hover:bg-[#edf5ee]">Batal</a>
            <button type="submit" class="bg-[#1f4d3d] hover:bg-[#173f34] text-white px-5 py-2 rounded-full text-sm font-medium transition duration-200">Simpan</button>
        </div>
    </form>
</div>
@endsection