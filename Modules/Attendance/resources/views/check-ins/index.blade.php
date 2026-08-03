@extends('shared::layouts.app')

@section('title', 'Daftar Check-In')

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
            <a href="{{ route('attendance.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-blue-100 bg-blue-900 hover:bg-blue-800 transition duration-200 translate-x-0 hover:-translate-x-1">
                <i class="fa fa-arrow-left text-md text-blue-50"></i>
            </a>
            <h1 class="text-xl font-semibold text-blue-800">Daftar Check-In</h1>
        </div>
        <a href="{{ route('attendance.check-ins.create') }}" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-full text-sm font-medium transition duration-200">
            <i class="fa fa-plus text-sm"></i> Tambah Check-In
        </a>
    </div>

    <div class="mt-6 bg-white shadow-md p-4">
        <div class="border border-gray-200 rounded-md grid grid-cols-6 gap-4 p-4 text-gray-700 font-semibold text-sm">
            <span>Nama</span>
            <span>Waktu</span>
            <span>Lokasi</span>
            <span>Jarak</span>
            <span>Status</span>
            <span class="flex justify-center">Aksi</span>
        </div>

        @forelse($checkIns as $checkIn)
        <div class="border border-gray-200 rounded-md grid grid-cols-6 gap-4 p-4 items-center text-gray-700 text-sm">
            <span class="font-medium text-slate-800">{{ $checkIn->employee->name ?? '-' }}</span>
            <span>{{ $checkIn->checked_at?->format('d M Y H:i:s') }}</span>
            <span>{{ $checkIn->location->name ?? '-' }}</span>
            <span>{{ $checkIn->distance_meters }} m</span>
            <span>{{ $checkIn->location ? 'Valid' : 'Tidak valid' }}</span>
            <div class="grid grid-cols-2 gap-2 w-full">
                <a href="{{ route('attendance.check-ins.edit', $checkIn->id) }}" class="bg-blue-900 hover:bg-blue-800 text-white rounded-full px-3 py-1 transition duration-200 flex items-center justify-center gap-1 hover:-translate-y-1 hover:shadow-lg">
                    <i class="fa fa-pen text-sm"></i> Edit
                </a>
                <form method="POST" action="{{ route('attendance.check-ins.destroy', $checkIn->id) }}" onsubmit="return confirm('Hapus check-in ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full bg-red-900 hover:bg-red-800 text-white rounded-full px-3 py-1 transition duration-200 flex items-center justify-center gap-1 hover:-translate-y-1 hover:shadow-lg">
                        <i class="fa fa-trash text-sm"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="border border-gray-200 rounded-md p-10 text-center text-sm text-slate-400">Belum ada data check-in.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $checkIns->links() }}</div>
</div>
@endsection