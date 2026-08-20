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
        <a href="{{ route('attendance.attendances.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-sky-800 hover:bg-sky-900 transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-sky-50"></i>
        </a>
        <div>
            <h1 class="text-md md:text-lg font-bold text-sky-800">{{ $attendance->employee->name }}</h1>
            <p class="text-xs md:text-sm text-sky-500">{{ $attendance->work_date->format('d M Y') }} &middot; {{ $attendance->shift->name ?? 'Tidak ada shift'}}</p>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Check-in --}}
        <div class="bg-sky-100 shadow-md p-6 rounded-lg">
            <div class="flex items-center gap-2">
                <i class="fa fa-clock text-sky-800"></i>
                <h3 class="text-md font-bold text-sky-800">Check-in</h3>
            </div>
            @if($attendance->checkIn)
            <div class="mt-3 flex flex-col md:flex-row md:justify-between gap-2">
                <div class="space-y-2 text-sm text-sky-800">
                    <div class="flex">
                        <span class="w-24 shrink-0 text-sky-500">Waktu</span>
                        <span>{{ $attendance->checkIn->checked_at->format('d M Y H:i:s') }}</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-sky-500">Lokasi</span>
                        <span>{{ $attendance->checkIn->location->name ?? '-' }}</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-sky-500">Jarak</span>
                        <span>{{ $attendance->checkIn->distance_meters }} m dari titik presensi</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-sky-500">Koordinat</span>
                        <span>{{ $attendance->checkIn->latitude }}, {{ $attendance->checkIn->longitude }}</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-sky-500">IP Address</span>
                        <span>{{ $attendance->checkIn->ip }}</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-sky-500">Perangkat</span>
                        <span>{{ $attendance->checkIn->device }}</span>
                    </div>
                </div>
                <div class="flex h-48 w-full md:h-full md:w-40 items-center justify-center rounded-lg bg-sky-50 text-sky-950 ring-3 ring-sky-500 overflow-hidden shadow-md">
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
        <div class="bg-sky-100 shadow-md p-6 rounded-lg">
            <div class="flex items-center gap-2">
                <i class="fa fa-clock text-sky-800"></i>
                <h3 class="text-md font-bold text-sky-800">Check-out</h3>
            </div>
            @if($attendance->checkOut)
            <div class="mt-3 flex flex-col md:flex-row md:justify-between gap-2">
                <div class="space-y-2 text-sm text-sky-800">
                    <div class="flex">
                        <span class="w-24 shrink-0 text-sky-500">Waktu</span>
                        <span>{{ $attendance->checkOut->checked_at->format('d M Y H:i:s') }}</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-sky-500">Lokasi</span>
                        <span>{{ $attendance->checkOut->location->name ?? '-' }}</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-sky-500">Jarak</span>
                        <span>{{ $attendance->checkOut->distance_meters }} m dari titik presensi</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-sky-500">Koordinat</span>
                        <span>{{ $attendance->checkOut->latitude }}, {{ $attendance->checkOut->longitude }}</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-sky-500">IP Address</span>
                        <span>{{ $attendance->checkOut->ip }}</span>
                    </div>

                    <div class="flex">
                        <span class="w-24 shrink-0 text-sky-500">Perangkat</span>
                        <span>{{ $attendance->checkOut->device }}</span>
                    </div>
                </div>

                <div class="flex h-48 w-full md:h-full md:w-40 items-center justify-center rounded-lg bg-sky-50 text-sky-950 ring-3 ring-sky-500 overflow-hidden shadow-md">
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
    <div class="mt-6 bg-sky-100 shadow-md p-6 rounded-lg">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <div class="flex items-center gap-2">
                <i class="fa fa-circle-check text-sky-800"></i>
                <h3 class="font-semibold text-sky-900">Status Kehadiran</h3>
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
                <label class="mb-1 block text-xs font-medium text-sky-500">Koreksi Status</label>
                <select name="attendance_status_id" class="rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                    <option value="">-- Pilih Status --</option>
                    @foreach($statuses as $status)
                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
                @error('attendance_status_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="mb-1 block text-xs font-medium text-sky-500">Alasan Koreksi</label>
                <input type="text" name="reason" value="{{ old('reason') }}" class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                @error('reason') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="bg-sky-800 hover:bg-sky-900 text-white px-5 py-2 rounded-full text-sm font-medium transition duration-200 transform translate-y-0 hover:translate-y-[-2px] cursor-pointer">Simpan Koreksi</button>
        </form>

        @if($corrections->isNotEmpty())
        <div class="mt-6 border-t border-sky-100 pt-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-sky-400">Riwayat Koreksi</p>
            <div class="mt-2 space-y-2">
                @foreach($corrections as $correction)
                <div class="rounded-lg bg-sky-50 px-3 py-2 text-sm">
                    <p class="text-sky-700">Diubah ke <strong>{{ $correction->newStatus->name }}</strong> oleh {{ $correction->correctedBy->username ?? 'Sistem' }}</p>
                    <p class="text-xs text-sky-500">{{ $correction->reason }} &middot; {{ $correction->created_at->format('d M Y H:i') }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

</div>
@endsection