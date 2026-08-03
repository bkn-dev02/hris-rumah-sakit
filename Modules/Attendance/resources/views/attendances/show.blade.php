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
        <a href="{{ route('attendance.attendances.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-blue-100 bg-blue-900 hover:bg-blue-800 transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-blue-50"></i>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-blue-800">{{ $attendance->employee->name }}</h1>
            <p class="text-sm text-slate-500">{{ $attendance->work_date->format('d M Y') }} &middot; Shift {{ $attendance->shift->name }}</p>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Check-in --}}
        <div class="bg-white shadow-md p-6">
            <h3 class="font-semibold text-slate-800">Check-in</h3>
            @if($attendance->check_in_at)
            <div class="mt-3 space-y-2 text-sm">
                <p><span class="text-slate-500">Waktu:</span> {{ $attendance->check_in_at->format('d M Y H:i:s') }}</p>
                <p><span class="text-slate-500">Lokasi:</span> {{ $attendance->checkInLocation->name ?? '-' }} ({{ $attendance->check_in_distance_meters }}m dari titik)</p>
                <p><span class="text-slate-500">Koordinat:</span> {{ $attendance->check_in_latitude }}, {{ $attendance->check_in_longitude }}</p>
                @if($attendance->check_in_photo)
                <img src="{{ asset('storage/' . $attendance->check_in_photo) }}" class="mt-2 h-40 w-40 rounded-lg object-cover">
                @endif
            </div>
            @else
            <p class="mt-3 text-sm text-slate-400">Belum check-in.</p>
            @endif
        </div>

        {{-- Check-out --}}
        <div class="bg-white shadow-md p-6">
            <h3 class="font-semibold text-slate-800">Check-out</h3>
            @if($attendance->check_out_at)
            <div class="mt-3 space-y-2 text-sm">
                <p><span class="text-slate-500">Waktu:</span> {{ $attendance->check_out_at->format('d M Y H:i:s') }}</p>
                <p><span class="text-slate-500">Lokasi:</span> {{ $attendance->checkOutLocation->name ?? '-' }} ({{ $attendance->check_out_distance_meters }}m dari titik)</p>
                <p><span class="text-slate-500">Koordinat:</span> {{ $attendance->check_out_latitude }}, {{ $attendance->check_out_longitude }}</p>
                @if($attendance->check_out_photo)
                <img src="{{ asset('storage/' . $attendance->check_out_photo) }}" class="mt-2 h-40 w-40 rounded-lg object-cover">
                @endif
            </div>
            @else
            <p class="mt-3 text-sm text-slate-400">Belum check-out.</p>
            @endif
        </div>
    </div>

    {{-- Status & koreksi --}}
    <div class="mt-6 bg-white shadow-md p-6">
        <h3 class="font-semibold text-slate-800">Status Kehadiran</h3>
        <div class="mt-3">
            @if($attendance->status)
            <x-shared::badge variant="success">{{ $attendance->status->name }}</x-shared::badge>
            <span class="ml-2 text-xs text-slate-400">({{ $attendance->determination_type === 'auto' ? 'Otomatis' : 'Manual' }})</span>
            @else
            <x-shared::badge variant="warning">Perlu Review</x-shared::badge>
            @endif
        </div>

        <form method="POST" action="{{ route('attendance.attendances.correct', $attendance->id) }}" class="mt-4 flex flex-wrap items-end gap-3">
            @csrf
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500">Koreksi Status</label>
                <select name="attendance_status_id" class="rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="">-- Pilih Status --</option>
                    @foreach($statuses as $status)
                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
                @error('attendance_status_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="mb-1 block text-xs font-medium text-slate-500">Alasan Koreksi</label>
                <input type="text" name="reason" value="{{ old('reason') }}" class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                @error('reason') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white px-5 py-2 rounded-full text-sm font-medium transition duration-200">Simpan Koreksi</button>
        </form>

        @if($corrections->isNotEmpty())
        <div class="mt-6 border-t border-slate-100 pt-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Riwayat Koreksi</p>
            <div class="mt-2 space-y-2">
                @foreach($corrections as $correction)
                <div class="rounded-lg bg-slate-50 px-3 py-2 text-sm">
                    <p class="text-slate-700">Diubah ke <strong>{{ $correction->newStatus->name }}</strong> oleh {{ $correction->correctedBy->username ?? 'Sistem' }}</p>
                    <p class="text-xs text-slate-500">{{ $correction->reason }} &middot; {{ $correction->created_at->format('d M Y H:i') }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

</div>
@endsection