@extends('shared::layouts.app')

@section('title', 'Riwayat Penempatan')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('master.employees.show', $employee->slug) }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-[#dfeee1] bg-[#1f4d3d] hover:bg-[#173f34] transition duration-200 translate-x-0 hover:-translate-x-1">
                <i class="fa fa-arrow-left text-md text-[#edf5ee]"></i>
            </a>
            <div>
                <h1 class="text-xl font-semibold text-[#1f4d3d]">Riwayat Penempatan</h1>
                <p class="text-sm text-[#2a684f]">{{ $employee->name }}</p>
            </div>
        </div>
        <a href="{{ route('master.employees.placements.create', $employee->slug) }}" class="bg-[#1f4d3d] hover:bg-[#173f34] text-white px-4 py-2 rounded-full text-sm font-medium transition duration-200">
            <i class="fa fa-plus text-sm"></i> Tempatkan / Mutasi
        </a>
    </div>

    <div class="mt-6 bg-white shadow-md p-4 rounded-xl border border-[#dfeee1]">
        <div class="border border-[#dfeee1] rounded-md grid grid-cols-6 gap-4 p-4 text-[#1f4d3d] font-semibold text-sm bg-[#edf5ee]">
            <span>Department</span>
            <span>Posisi</span>
            <span>Tipe</span>
            <span>Mulai</span>
            <span>Berakhir</span>
            <span>Catatan</span>
        </div>

        @forelse($history as $placement)
        <div class="border border-[#dfeee1] rounded-md grid grid-cols-6 gap-4 p-4 items-center text-[#1f4d3d] text-sm">
            <span>{{ $placement->department->name }}</span>
            <span>{{ $placement->position->name }}</span>
            <span>
                @php
                $typeLabels = [
                'initial' => ['Awal', 'primary'],
                'mutation' => ['Mutasi', 'info'],
                'promotion' => ['Promosi', 'success'],
                'demotion' => ['Demosi', 'warning'],
                'temporary' => ['Sementara', 'secondary'],
                ];
                [$label, $variant] = $typeLabels[$placement->placement_type] ?? [$placement->placement_type, 'secondary'];
                @endphp
                <x-shared::badge :variant="$variant" size="sm">{{ $label }}</x-shared::badge>
            </span>
            <span>{{ $placement->start_date->format('d M Y') }}</span>
            <span>{{ $placement->end_date?->format('d M Y') ?? '—' }}</span>
            <span class="text-slate-500">{{ $placement->notes ?? '-' }}</span>
        </div>
        @empty
        <div class="border border-[#dfeee1] rounded-md p-10 text-center text-sm text-slate-400 bg-[#f8fbf8]">Belum ada riwayat penempatan.</div>
        @endforelse
    </div>
</div>
@endsection