@extends('shared::layouts.app')

@section('title', 'Lokasi Absensi')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('attendance.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-[#dfeee1] bg-[#173f34] hover:bg-[#173f34] transition duration-200 translate-x-0 hover:-translate-x-1">
                <i class="fa fa-arrow-left text-md text-[#edf5ee]"></i>
            </a>
            <h1 class="text-xl font-semibold text-[#1f4d3d]">Lokasi Absensi</h1>
        </div>
        <a href="{{ route('attendance.locations.create') }}" class="bg-[#173f34] hover:bg-[#173f34] text-white px-4 py-2 rounded-full text-sm font-medium transition duration-200">
            <i class="fa fa-plus text-sm"></i> Tambah Lokasi
        </a>
    </div>

    <div class="mt-6 bg-white shadow-md p-4">
        <div class="border border-[#dfeee1] rounded-md grid grid-cols-5 gap-4 p-4 text-[#2a684f] font-semibold text-sm">
            <span>Nama</span>
            <span>Koordinat</span>
            <span>Radius</span>
            <span>Status</span>
            <span class="flex justify-center">Aksi</span>
        </div>

        @forelse($locations as $location)
        <div class="border border-[#dfeee1] rounded-md grid grid-cols-5 gap-4 p-4 items-center text-[#2a684f] text-sm">
            <span class="font-medium text-[#1f4d3d]">{{ $location->name }}</span>
            <span class="text-[#2a684f]">{{ $location->latitude }}, {{ $location->longitude }}</span>
            <span>{{ $location->radius_meters }} m</span>
            <span>
                @if($location->is_active)
                <x-shared::badge variant="success" dot>Aktif</x-shared::badge>
                @else
                <x-shared::badge variant="secondary" dot>Nonaktif</x-shared::badge>
                @endif
            </span>
            <div class="grid grid-cols-2 gap-2 w-full">
                <a href="{{ route('attendance.locations.edit', $location->id) }}" class="bg-[#173f34] hover:bg-[#173f34] text-white rounded-full px-3 py-1 transition duration-200 flex items-center justify-center gap-1 hover:-transky-y-1 hover:shadow-lg">
                    <i class="fa fa-pen text-sm"></i> Edit
                </a>
                <form method="POST" action="{{ route('attendance.locations.destroy', $location->id) }}" onsubmit="return confirm('Hapus lokasi ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full bg-red-900 hover:bg-red-800 text-white rounded-full px-3 py-1 transition duration-200 flex items-center justify-center gap-1 hover:-translate-y-1 hover:shadow-lg">
                        <i class="fa fa-trash text-sm"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="border border-[#dfeee1] rounded-md p-10 text-center text-sm text-[#2a684f]">Belum ada lokasi absensi.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $locations->links() }}</div>

</div>
@endsection
