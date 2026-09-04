@extends('shared::layouts.app')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="mx-auto max-w-7xl px-3 py-4 sm:px-6 sm:py-8">
    <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#edf5ee] text-[#173f34]">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
        <div>
            <h1 class="text-xl font-bold text-[#1f4d3d]">Riwayat Absensi</h1>
            <p class="text-sm text-[#567564]">Riwayat presensi 30 hari terakhir</p>
        </div>
    </div>

    <div class="mt-6 space-y-3">
        @forelse ($history as $attendance)
        <div class="rounded-xl border border-[#dfeee1] bg-white p-4 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-semibold text-[#1f4d3d]">{{ $attendance['work_date'] }}</p>
                    <p class="mt-1 text-sm text-[#567564]">{{ $attendance['shift_name'] ?? 'Tidak ada shift' }}</p>
                </div>
                <div class="grid grid-cols-2 gap-6 text-sm sm:grid-cols-5">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-[#8aa296]">Check-in</p>
                        <p class="mt-1 font-semibold text-[#1f4d3d]">{{ $attendance['check_in_time'] ?? '--:--' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-[#8aa296]">Bukti Masuk</p>
                        @if ($attendance['check_in_photo_url'])
                        <a href="{{ $attendance['check_in_photo_url'] }}" target="_blank" rel="noopener" class="mt-1 block h-14 w-14 overflow-hidden rounded-lg border border-[#dfeee1] bg-[#edf5ee]">
                            <img src="{{ $attendance['check_in_photo_url'] }}" alt="Bukti foto check-in" class="h-full w-full object-cover">
                        </a>
                        @else
                        <span class="mt-1 flex h-14 w-14 items-center justify-center rounded-lg border border-dashed border-[#dfeee1] bg-[#f8fbf8] text-[#8aa296]">
                            <i class="fa-solid fa-image-slash text-sm"></i>
                        </span>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-[#8aa296]">Check-out</p>
                        <p class="mt-1 font-semibold text-[#1f4d3d]">{{ $attendance['check_out_time'] ?? '--:--' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-[#8aa296]">Bukti Pulang</p>
                        @if ($attendance['check_out_photo_url'])
                        <a href="{{ $attendance['check_out_photo_url'] }}" target="_blank" rel="noopener" class="mt-1 block h-14 w-14 overflow-hidden rounded-lg border border-[#dfeee1] bg-[#edf5ee]">
                            <img src="{{ $attendance['check_out_photo_url'] }}" alt="Bukti foto check-out" class="h-full w-full object-cover">
                        </a>
                        @else
                        <span class="mt-1 flex h-14 w-14 items-center justify-center rounded-lg border border-dashed border-[#dfeee1] bg-[#f8fbf8] text-[#8aa296]">
                            <i class="fa-solid fa-image-slash text-sm"></i>
                        </span>
                        @endif
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-xs uppercase tracking-wide text-[#8aa296]">Status</p>
                        <p class="mt-1 font-semibold text-[#1f4d3d]">{{ $attendance['attendance_status'] ?? 'Belum ditentukan' }}</p>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="rounded-xl border border-[#dfeee1] bg-white p-10 text-center text-sm text-[#567564]">
            Belum ada riwayat absensi.
        </div>
        @endforelse
    </div>
</div>
@endsection