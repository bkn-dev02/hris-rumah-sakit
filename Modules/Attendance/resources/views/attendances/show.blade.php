@extends('shared::layouts.app')

@section('title', 'Detail Absensi')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="flex items-center gap-4">
        <a href="{{ route('attendance.attendances.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#2a684f] hover:bg-[#173f34] transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-[#edf5ee]"></i>
        </a>
        <div>
            <h1 class="text-md md:text-lg font-bold text-[#1f4d3d]">{{ $attendance->employee->name }}</h1>
            <p class="text-xs md:text-sm text-[#2a684f]">{{ $attendance->work_date->format('d M Y') }} &middot; {{ $attendance->shift->name ?? 'Tidak ada shift'}} ({{ $attendance->shift->start_time?->format('H:i') ?? 'Tidak ada shift' }} - {{ $attendance->shift->end_time?->format('H:i') ?? 'Tidak ada shift' }})</p>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Check-in --}}
        <div class="bg-[#edf5ee] shadow-md p-6 rounded-lg">
            <div class="flex items-center gap-2">
                <i class="fa fa-clock text-[#1f4d3d]"></i>
                <h3 class="text-md font-bold text-[#1f4d3d]">Check-in</h3>
            </div>
            @if($attendance->checkIn)
            <div class="mt-3 flex flex-col md:flex-row md:justify-between gap-2">
                <div class="space-y-2 text-sm text-[#1f4d3d]">
                    <div class="flex">
                        <span class="w-24 shrink-0 text-[#2a684f]">Waktu</span>
                        <span>{{ $attendance->checkIn->checked_at->format('d M Y H:i:s') }}</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-[#2a684f]">Lokasi</span>
                        <span>{{ $attendance->checkIn->location->name ?? '-' }}</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-[#2a684f]">Jarak</span>
                        <span>{{ $attendance->checkIn->distance_meters }} m dari titik presensi</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-[#2a684f]">Koordinat</span>
                        <span>{{ $attendance->checkIn->latitude }}, {{ $attendance->checkIn->longitude }}</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-[#2a684f]">IP Address</span>
                        <span>{{ $attendance->checkIn->ip }}</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-[#2a684f]">Perangkat</span>
                        <span>{{ $attendance->checkIn->device }}</span>
                    </div>
                </div>
                <div class="flex h-48 w-full md:h-full md:w-40 items-center justify-center rounded-lg bg-[#f8fbf8] text-[#173f34] ring-3 ring-sky-500 overflow-hidden shadow-md">
                    @if($attendance->checkIn->photo)
                    <img
                        src="{{ asset('storage/' . $attendance->checkIn->photo) }}"
                        class="h-full w-full object-cover">
                    @endif
                </div>
            </div>
            @else
            <p class="mt-3 text-sm text-slate-400">Belum check-in.</p>
            @endif
        </div>

        {{-- Check-out --}}
        <div class="bg-[#edf5ee] shadow-md p-6 rounded-lg">
            <div class="flex items-center gap-2">
                <i class="fa fa-clock text-[#1f4d3d]"></i>
                <h3 class="text-md font-bold text-[#1f4d3d]">Check-out</h3>
            </div>
            @if($attendance->checkOut)
            <div class="mt-3 flex flex-col md:flex-row md:justify-between gap-2">
                <div class="space-y-2 text-sm text-[#1f4d3d]">
                    <div class="flex">
                        <span class="w-24 shrink-0 text-[#2a684f]">Waktu</span>
                        <span>{{ $attendance->checkOut->checked_at->format('d M Y H:i:s') }}</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-[#2a684f]">Lokasi</span>
                        <span>{{ $attendance->checkOut->location->name ?? '-' }}</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-[#2a684f]">Jarak</span>
                        <span>{{ $attendance->checkOut->distance_meters }} m dari titik presensi</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-[#2a684f]">Koordinat</span>
                        <span>{{ $attendance->checkOut->latitude }}, {{ $attendance->checkOut->longitude }}</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-[#2a684f]">IP Address</span>
                        <span>{{ $attendance->checkOut->ip }}</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-[#2a684f]">Perangkat</span>
                        <span>{{ $attendance->checkOut->device }}</span>
                    </div>
                </div>

                <div class="flex h-48 w-full md:h-full md:w-40 items-center justify-center rounded-lg bg-[#f8fbf8] text-[#173f34] ring-3 ring-sky-500 overflow-hidden shadow-md">
                    @if($attendance->checkOut->photo)
                    <img
                        src="{{ asset('storage/' . $attendance->checkOut->photo) }}"
                        class="h-full w-full object-cover">
                    @endif
                </div>
            </div>
            @else
            <p class="mt-3 text-sm text-slate-400">Belum check-out.</p>
            @endif
        </div>
    </div>

    {{-- Status & koreksi --}}
    <div class="mt-6 bg-[#edf5ee] shadow-md p-6 rounded-lg">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <div class="flex items-center gap-2">
                <i class="fa fa-circle-check text-[#1f4d3d]"></i>
                <h3 class="font-semibold text-[#1f4d3d]">Status Kehadiran</h3>
            </div>
            <div class="">
                @if($attendance->status)
                <x-shared::badge variant="success">{{ $attendance->status->name }}</x-shared::badge>
                <span class="ml-2 text-xs text-slate-400">({{ $attendance->determination_type === 'auto' ? 'Otomatis' : 'Manual' }})</span>
                @else
                <x-shared::badge variant="warning">Perlu Review</x-shared::badge>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('attendance.attendances.correct', $attendance->id) }}" class="mt-4 flex flex-wrap items-end gap-3">
            @csrf
            <div>
                <label class="mb-1 block text-xs font-medium text-[#2a684f]">Koreksi Status</label>
                <select name="attendance_status_id" class="rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
                    <option value="">-- Pilih Status --</option>
                    @foreach($statuses as $status)
                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
                @error('attendance_status_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="mb-1 block text-xs font-medium text-[#2a684f]">Alasan Koreksi</label>
                <input type="text" name="reason" value="{{ old('reason') }}" class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
                @error('reason') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="bg-[#2a684f] hover:bg-[#173f34] text-white px-5 py-2 rounded-full text-sm font-medium transition duration-200 transform translate-y-0 hover:translate-y-[-2px] cursor-pointer">Simpan Koreksi</button>
        </form>

        @if($corrections->isNotEmpty())
        <div class="mt-6 border-t border-[#dfeee1] pt-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-[#2a684f]">Riwayat Koreksi</p>
            <div class="mt-2 space-y-2">
                @foreach($corrections as $correction)
                <div class="rounded-lg bg-[#f8fbf8] px-3 py-2 text-sm">
                    <p class="text-[#2a684f]">Diubah ke <strong>{{ $correction->newStatus->name }}</strong> oleh {{ $correction->correctedBy->username ?? 'Sistem' }}</p>
                    <p class="text-xs text-[#2a684f]">{{ $correction->reason }} &middot; {{ $correction->created_at->format('d M Y H:i') }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

</div>
@endsection