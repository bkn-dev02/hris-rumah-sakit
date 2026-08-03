@extends('shared::layouts.app')

@section('title', 'Shift')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('master.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-blue-100 bg-blue-900 hover:bg-blue-800 transition duration-200 translate-x-0 hover:-translate-x-1">
                <i class="fa fa-arrow-left text-md text-blue-50"></i>
            </a>
            <h1 class="text-xl font-semibold text-slate-800">Manajemen Shift</h1>
        </div>
        <a href="{{ route('master.shifts.create') }}">
            <x-shared::button variant="primary" icon="fa-solid fa-plus">Tambah Shift</x-shared::button>
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg border border-slate-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Jam Kerja</th>
                    <th class="px-4 py-3">Berlaku Sejak</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($shifts as $shift)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $shift->name }}</td>
                    <td class="px-4 py-3">
                        <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-600">{{ $shift->code }}</code>
                    </td>
                    <td class="px-4 py-3 text-slate-600">{{ $shift->start_time }} - {{ $shift->end_time }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $shift->effective_date->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('master.shifts.history', $shift->code) }}" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600" title="Riwayat"><i class="fa-solid fa-clock-rotate-left"></i></a>
                            <a href="{{ route('master.shifts.editVersion', $shift->id) }}" class="rounded-lg p-2 text-slate-400 hover:bg-blue-50 hover:text-blue-600" title="Ubah (versi baru)"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" action="{{ route('master.shifts.destroy', $shift->id) }}" onsubmit="return confirm('Hapus shift ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded-lg p-2 text-slate-400 hover:bg-red-50 hover:text-red-600" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-400">Belum ada shift.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $shifts->links() }}</div>
</div>
@endsection