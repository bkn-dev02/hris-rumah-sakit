@extends('shared::layouts.app')

@section('title', 'Positions')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-8">
    @if(session('success'))
    <div>
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        {{ session('error') }}
    </div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('master.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-[#dfeee1] bg-[#173f34] hover:bg-[#173f34] transition duration-200 translate-x-0 hover:-translate-x-1">
                <i class="fa fa-arrow-left text-md text-[#edf5ee]"></i>
            </a>
            <h1 class="text-xl font-semibold text-slate-800">Manajemen Posisi</h1>
        </div>
        <a href="{{ route('master.positions.create') }}">
            <x-shared::button variant="primary" icon="fa-solid fa-plus">Tambah Posisi</x-shared::button>
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg border border-[#dfeee1] bg-white">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-[#dfeee1] bg-[#f8fbf8] text-xs uppercase tracking-wide text-[#2a684f]">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Level</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($positions as $position)
                <tr class="hover:bg-[#f8fbf8]">
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $position->name }}</td>
                    <td class="px-4 py-3">
                        <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-600">{{ $position->code }}</code>
                    </td>
                    <td class="px-4 py-3">
                        <x-shared::badge variant="primary" size="sm">Level {{ $position->level }}</x-shared::badge>
                    </td>
                    <td class="px-4 py-3">
                        @if($position->is_active)
                        <x-shared::badge variant="success" dot>Aktif</x-shared::badge>
                        @else
                        <x-shared::badge variant="secondary" dot>Nonaktif</x-shared::badge>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('master.positions.edit', $position->id) }}" class="rounded-lg p-2 text-[#2a684f] hover:bg-[#f8fbf8] hover:text-[#2a684f]"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" action="{{ route('master.positions.destroy', $position->id) }}" onsubmit="return confirm('Hapus position ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded-lg p-2 text-[#2a684f] hover:bg-red-50 hover:text-red-600"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-10 text-center text-sm text-[#2a684f]">Belum ada position.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
