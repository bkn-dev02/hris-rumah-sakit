@extends('shared::layouts.app')

@section('title', 'Role Management')

@section('content')


<div class="max-w-7xl mx-auto px-6 py-8">

    @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        {{ session('error') }}
    </div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <a
                href="{{ route('security.index') }}"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-900 text-white shadow-sm shadow-sky-900/20 transition-all duration-200 hover:-translate-x-0.5 hover:bg-sky-800 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700"
                title="Kembali ke Security Module">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold tracking-tight text-sky-800">Role Management</h1>
                <p class="text-sm text-sky-500">Kelola role dan hak akses pengguna.</p>
            </div>
        </div>
        <a href="{{ route('security.roles.create') }}">
            <x-shared::button variant="primary" icon="fa-solid fa-plus">Tambah Role</x-shared::button>
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg border border-slate-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Permission</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($roles as $role)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3">
                        <p class="font-medium text-slate-800">
                            {{ $role->name }}
                            @if($role->is_system)
                            <x-shared::badge variant="secondary" size="sm">Sistem</x-shared::badge>
                            @endif
                        </p>
                        <p class="text-xs text-slate-500">{{ $role->code }}</p>
                    </td>
                    <td class="px-4 py-3 text-slate-600">{{ $role->permissions_count }} permission</td>
                    <td class="px-4 py-3">
                        @if($role->is_active)
                        <x-shared::badge variant="success" dot>Aktif</x-shared::badge>
                        @else
                        <x-shared::badge variant="secondary" dot>Nonaktif</x-shared::badge>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex justify-end gap-2">
                            @if($role->is_system)
                            <span class="rounded-lg p-2 text-slate-300" title="Role sistem tidak dapat diubah">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            @else
                            <a href="{{ route('security.roles.edit', $role->id) }}" class="rounded-lg p-2 text-slate-400 hover:bg-blue-50 hover:text-blue-600" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('security.roles.destroy', $role->id) }}" onsubmit="return confirm('Hapus role ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg p-2 text-slate-400 hover:bg-red-50 hover:text-red-600" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-10 text-center text-sm text-slate-400">
                        Belum ada role.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $roles->links() }}
    </div>
</div>
@endsection