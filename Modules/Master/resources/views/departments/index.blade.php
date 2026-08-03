@extends('shared::layouts.app')

@section('title', 'Department')

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
            <a href="{{ route('master.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-sky-900 hover:bg-sky-950 transition duration-200 translate-x-0 hover:-translate-x-1">
                <i class="fa fa-arrow-left text-md text-sky-200"></i>
            </a>
            <h1 class="text-lg font-bold text-sky-800">Manajemen Department</h1>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('master.departments.create') }}">
                <x-shared::button variant="primary" icon="fa-solid fa-plus">
                    <span class="text-sky-200">Tambah Department</span>
                </x-shared::button>
            </a>
            <a href="{{ route('master.departments.tree') }}">
                <x-shared::button variant="primary" icon="fa fa-sitemap text-sm">
                    <span class="text-sky-200">Lihat Struktur</span>
                </x-shared::button>
            </a>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg border border-sky-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-sky-200 bg-sky-50 text-xs uppercase tracking-wide text-sky-900">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Induk</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sky-100">
                @forelse($departments as $department)
                <tr class="hover:bg-sky-50">
                    <td class="px-4 py-3 font-medium text-sky-800">{{ $department->name }}</td>
                    <td class="px-4 py-3">
                        <code class="rounded bg-sky-100 px-1.5 py-0.5 text-xs text-sky-600">{{ $department->code }}</code>
                    </td>
                    <td class="px-4 py-3 text-sky-500">{{ $department->parent?->name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($department->is_active)
                        <x-shared::badge variant="success" dot>Aktif</x-shared::badge>
                        @else
                        <x-shared::badge variant="secondary" dot>Nonaktif</x-shared::badge>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('master.departments.edit', $department->id) }}" class="rounded-lg p-2 text-sky-400 hover:bg-blue-50 hover:text-blue-600" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('master.departments.destroy', $department->id) }}" onsubmit="return confirm('Hapus department ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg p-2 text-sky-400 hover:bg-red-50 hover:text-red-600" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-10 text-center text-sm text-sky-400">Belum ada department.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $departments->links() }}</div>
</div>
</div>
@endsection