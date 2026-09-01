@extends('shared::layouts.app')

@section('title', 'Shift')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('master.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-[#dfeee1] bg-[#173f34] hover:bg-[#173f34] transition duration-200 translate-x-0 hover:-translate-x-1">
                <i class="fa fa-arrow-left text-md text-[#edf5ee]"></i>
            </a>
            <h1 class="text-xl font-semibold text-[#1f4d3d]">Manajemen Shift</h1>
        </div>
        <a href="{{ route('master.shifts.create') }}">
            <x-shared::button variant="primary" icon="fa-solid fa-plus">Tambah Shift</x-shared::button>
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg border border-[#dfeee1] bg-white">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-[#dfeee1] bg-[#f8fbf8] text-xs uppercase tracking-wide text-[#2a684f]">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Jam Kerja</th>
                    <th class="px-4 py-3">Berlaku Sejak</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sky-100">
                @forelse($shifts as $shift)
                <tr class="hover:bg-[#f8fbf8]">
                    <td class="px-4 py-3 font-medium text-[#1f4d3d]">{{ $shift->name }}</td>
                    <td class="px-4 py-3">
                        <code class="rounded bg-[#edf5ee] px-1.5 py-0.5 text-xs text-[#2a684f]">{{ $shift->code }}</code>
                    </td>
                    <td class="px-4 py-3 text-[#2a684f]">{{ $shift->start_time }} - {{ $shift->end_time }}</td>
                    <td class="px-4 py-3 text-[#2a684f]">{{ $shift->effective_date->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('master.shifts.history', $shift->code) }}" class="rounded-lg p-2 text-[#2a684f] hover:bg-[#edf5ee] hover:text-[#2a684f]" title="Riwayat"><i class="fa-solid fa-clock-rotate-left"></i></a>
                            <a href="{{ route('master.shifts.editVersion', $shift->id) }}" class="rounded-lg p-2 text-[#2a684f] hover:bg-[#edf5ee] hover:text-[#2a684f]" title="Ubah (versi baru)"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" action="{{ route('master.shifts.destroy', $shift->id) }}" onsubmit="return confirm('Hapus shift ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded-lg p-2 text-[#2a684f] hover:bg-red-50 hover:text-red-600" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-10 text-center text-sm text-[#2a684f]">Belum ada shift.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $shifts->links() }}</div>
</div>
@endsection
