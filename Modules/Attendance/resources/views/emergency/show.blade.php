@extends('shared::layouts.app')

@section('title', 'Detail Presensi Darurat')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <a href="{{ url()->previous() }}" class="text-sm text-slate-500 hover:text-slate-700 flex items-center gap-2 mb-4">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    {{-- Info Pegawai & Status --}}
    <div class="bg-white rounded-2xl shadow-md p-5 mb-4">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-full bg-slate-100 flex items-center justify-center text-sm font-semibold text-slate-600">
                    {{ collect(explode(' ', $checkIn->employee->name))->map(fn($w) => $w[0] ?? '')->take(2)->implode('') }}
                </div>
                <div>
                    <div class="font-semibold text-slate-800">{{ $checkIn->employee->name }}</div>
                    <div class="text-xs text-slate-400">{{ $checkIn->checked_at->translatedFormat('d F Y, H:i') }}</div>
                </div>
            </div>

            @php
            $meta = [
            'pending' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'dot' => 'bg-amber-500', 'label' => 'Pending'],
            'approved' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500', 'label' => 'Disetujui'],
            'rejected' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'dot' => 'bg-rose-500', 'label' => 'Ditolak'],
            ][$checkIn->emergency_status];
            @endphp
            <span class="text-xs px-3 py-1 rounded-full font-medium flex items-center gap-1.5 {{ $meta['bg'] }} {{ $meta['text'] }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $meta['dot'] }}"></span>
                {{ $meta['label'] }}
            </span>
        </div>

        <div class="text-sm">
            <div class="text-xs text-slate-400 mb-1">Alasan</div>
            <p class="text-slate-700">{{ $checkIn->emergency_reason }}</p>
        </div>

        @if ($checkIn->emergency_status !== 'pending')
        <div class="mt-4 pt-4 border-t border-slate-100 text-sm">
            <div class="text-xs text-slate-400 mb-1">
                {{ $checkIn->emergency_status === 'approved' ? 'Disetujui' : 'Ditolak' }} oleh
            </div>
            <p class="text-slate-700">
                {{ $checkIn->emergencyDecidedBy?->name ?? '-' }} · {{ $checkIn->emergency_decided_at?->translatedFormat('d M Y, H:i') }}
            </p>
            @if ($checkIn->emergency_decision_note)
            <p class="text-slate-500 text-xs mt-1">"{{ $checkIn->emergency_decision_note }}"</p>
            @endif
        </div>
        @endif
    </div>

    {{-- Foto --}}
    <div class="bg-white rounded-2xl shadow-md p-5 mb-4">
        <div class="text-sm font-medium text-slate-600 mb-3">Foto</div>
        <div class="grid grid-cols-2 gap-3">
            @if ($checkIn->photo)
            <img src="{{ asset('storage/' . $checkIn->photo) }}" alt="Foto check-in" class="rounded-xl w-full h-40 object-cover">
            @endif
            @if ($checkIn->emergency_photo)
            <img src="{{ asset('storage/' . $checkIn->emergency_photo) }}" alt="Foto darurat" class="rounded-xl w-full h-40 object-cover">
            @endif
        </div>
    </div>

    {{-- Lokasi --}}
    <div class="bg-white rounded-2xl shadow-md p-5">
        <div class="text-sm font-medium text-slate-600 mb-3">Lokasi</div>
        <div id="emergency-map" style="height: 300px; border-radius: 12px;"></div>
        <div class="text-xs text-slate-400 mt-2">
            {{ $checkIn->latitude }}, {{ $checkIn->longitude }}
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const map = L.map('emergency-map').setView([{{ $checkIn->latitude }}, {{ $checkIn->longitude }}], 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);
    L.marker([{{ $checkIn->latitude }}, {{ $checkIn->longitude }}]).addTo(map);
</script>
@endsection