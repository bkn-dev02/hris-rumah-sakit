@extends('shared::layouts.app')

@section('title', 'Presensi Darurat')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-8">
    <div class="mb-6 rounded-xl bg-sky-100 px-5 py-4 shadow-sm">
        <h1 class="text-xl font-bold text-sky-950">Presensi Darurat</h1>
        <p class="mt-0.5 text-xs text-slate-500">Menunggu persetujuan HRD</p>
    </div>

    @if (session('success'))
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
        {{ session('error') }}
    </div>
    @endif

    <div class="space-y-4">
        @forelse ($checkIns as $checkIn)
        <div class="rounded-xl border border-sky-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:justify-between">
                <div class="flex-1">
                    <p class="font-semibold text-sky-950">{{ $checkIn->employee->name }}</p>
                    <p class="text-xs text-slate-400">{{ $checkIn->checked_at->format('d M Y, H:i') }}</p>
                    <p class="mt-2 text-sm text-slate-700">{{ $checkIn->emergency_reason }}</p>

                    <a href="https://maps.google.com/?q={{ $checkIn->latitude }},{{ $checkIn->longitude }}"
                        target="_blank"
                        class="mt-2 inline-flex items-center gap-1 text-xs text-sky-700 hover:underline">
                        <i class="fa-solid fa-location-dot"></i>
                        Lihat lokasi di peta
                    </a>

                    <div class="mt-3 flex gap-3">
                        <a href="{{ Storage::url($checkIn->photo) }}" target="_blank" class="block">
                            <img src="{{ Storage::url($checkIn->photo) }}" class="h-20 w-20 rounded-lg object-cover border border-sky-200" alt="Selfie">
                        </a>
                        <a href="{{ Storage::url($checkIn->emergency_photo) }}" target="_blank" class="block">
                            <img src="{{ Storage::url($checkIn->emergency_photo) }}" class="h-20 w-20 rounded-lg object-cover border border-sky-200" alt="Bukti">
                        </a>
                    </div>
                </div>

                <div class="flex gap-2 sm:flex-col">
                    <form action="{{ route('attendance.emergency.decide', $checkIn->id) }}" method="POST" onsubmit="return confirm('Setujui presensi darurat ini?');">
                        @csrf
                        <input type="hidden" name="decision" value="approve">
                        <button type="submit" class="w-full rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                            <i class="fa-solid fa-check"></i> Setujui
                        </button>
                    </form>
                    <form action="{{ route('attendance.emergency.decide', $checkIn->id) }}" method="POST" onsubmit="return confirm('Tolak presensi darurat ini?');">
                        @csrf
                        <input type="hidden" name="decision" value="reject">
                        <button type="submit" class="w-full rounded-lg bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-700">
                            <i class="fa-solid fa-xmark"></i> Tolak
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="rounded-xl border border-sky-200 bg-white p-10 text-center text-sm text-slate-500 shadow-sm">
            Tidak ada presensi darurat yang menunggu persetujuan.
        </div>
        @endforelse
    </div>
</div>
@endsection