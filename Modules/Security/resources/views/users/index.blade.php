@extends('shared::layouts.app')

@section('title', 'User Management')

@section('content')

<div class="min-h-full bg-slate-50">

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        @if(session('success'))

        <div class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">

            <i class="fa-solid fa-circle-check mt-0.5"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

        @endif

        <div class="mb-8">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">

                    {{-- Back Button --}}
                    <a
                        href="{{ route('security.index') }}"
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-900 text-white shadow-sm shadow-sky-900/20 transition-all duration-200 hover:-translate-x-0.5 hover:bg-sky-800 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700"
                        title="Kembali ke Security Module">
                        <i class="fa-solid fa-arrow-left text-sm"></i>
                    </a>

                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-sky-800">
                            User Management
                        </h1>

                        <p class="mt-1 text-sm text-sky-500">
                            Kelola akun pengguna, status akun, dan akses pengguna sistem HRIS.
                        </p>

                    </div>

                </div>


                {{-- Add User --}}
                <a href="{{ route('security.users.create') }}">

                    <x-shared::button
                        variant="primary"
                        icon="fa-solid fa-plus">
                        Tambah User
                    </x-shared::button>

                </a>

            </div>

        </div>


        {{-- =========================================================
            STATUS TABS
        ========================================================== --}}
        <div class="mb-6">

            <div class="inline-flex rounded-xl border border-slate-200 bg-white p-1 shadow-sm">

                {{-- Active --}}
                <a
                    href="{{ route('security.users.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-all duration-200
                    {{ !request('trashed')
                        ? 'bg-sky-900 text-white shadow-sm'
                        : 'text-slate-500 hover:bg-sky-50 hover:text-sky-800'
                    }}">

                    <span
                        class="h-2 w-2 rounded-full
                        {{ !request('trashed') ? 'bg-emerald-300' : 'bg-emerald-500' }}"></span>

                    Aktif

                </a>


                {{-- Deleted --}}
                <a
                    href="{{ route('security.users.index', ['trashed' => 1]) }}"
                    class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-all duration-200
                    {{ request('trashed')
                        ? 'bg-sky-900 text-white shadow-sm'
                        : 'text-slate-500 hover:bg-sky-50 hover:text-sky-800'
                    }}">

                    <span
                        class="h-2 w-2 rounded-full
                        {{ request('trashed') ? 'bg-red-300' : 'bg-red-500' }}"></span>

                    Terhapus

                </a>

            </div>

        </div>


        {{-- =========================================================
            USER TABLE
        ========================================================== --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="w-full min-w-[640px] text-left text-sm">

                    {{-- Table Header --}}
                    <thead class="border-b border-slate-200 bg-slate-50">

                        <tr>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                User
                            </th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Role
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Status
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    {{-- Table Body --}}
                    <tbody class="divide-y divide-slate-100">

                        @forelse($users as $user)

                        <tr class="group transition-colors duration-200 hover:bg-sky-50/40">


                            {{-- User --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <x-shared::avatar
                                        name="{{ $user->username }}"
                                        size="sm" />

                                    <div class="min-w-0">

                                        <p class="font-semibold text-slate-800">
                                            {{ $user->username }}
                                        </p>

                                        <p class="mt-0.5 text-xs text-slate-500">
                                            {{ $user->email }}
                                        </p>

                                    </div>

                                </div>

                            </td>

                            <td class="px-6 py-4">

                                @if($user->roles->isNotEmpty())

                                <div class="flex flex-wrap gap-1.5">

                                    @foreach($user->roles as $role)

                                    <span class="inline-flex items-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-medium text-sky-700 ring-1 ring-inset ring-sky-200">
                                        {{ $role->name }}
                                    </span>

                                    @endforeach

                                </div>

                                @else

                                <span class="text-sm text-slate-400">
                                    Belum ada role
                                </span>

                                @endif

                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">

                                @if($user->trashed())

                                <x-shared::badge
                                    variant="danger"
                                    dot>
                                    Terhapus
                                </x-shared::badge>

                                @elseif($user->is_active)

                                <x-shared::badge
                                    variant="success"
                                    dot>
                                    Aktif
                                </x-shared::badge>

                                @else

                                <x-shared::badge
                                    variant="secondary"
                                    dot>
                                    Nonaktif
                                </x-shared::badge>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="px-6 py-4">

                                <div class="flex justify-end gap-1.5">

                                    @if($user->trashed())

                                    {{-- Restore --}}
                                    <form
                                        method="POST"
                                        action="{{ route('security.users.restore', $user->slug) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition-all duration-200 hover:bg-emerald-50 hover:text-emerald-600 hover:shadow-sm"
                                            title="Pulihkan">
                                            <i class="fa-solid fa-rotate-left text-sm"></i>
                                        </button>

                                    </form>


                                    {{-- Permanent Delete --}}
                                    <form
                                        method="POST"
                                        action="{{ route('security.users.forceDelete', $user->slug) }}"
                                        onsubmit="return confirm('Hapus permanen? Tindakan ini tidak bisa dibatalkan.')">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition-all duration-200 hover:bg-red-50 hover:text-red-600 hover:shadow-sm"
                                            title="Hapus Permanen">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>

                                    </form>

                                    @else

                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('security.users.edit', $user->slug) }}"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition-all duration-200 hover:bg-sky-50 hover:text-sky-700 hover:shadow-sm"
                                        title="Edit User">
                                        <i class="fa-solid fa-pen text-sm"></i>
                                    </a>


                                    {{-- Delete --}}
                                    <form
                                        method="POST"
                                        action="{{ route('security.users.destroy', $user->slug) }}"
                                        onsubmit="return confirm('Hapus user ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition-all duration-200 hover:bg-red-50 hover:text-red-600 hover:shadow-sm"
                                            title="Hapus User">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>

                                    </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                        @empty

                        {{-- Empty State --}}
                        <tr>

                            <td colspan="3" class="px-6 py-16">

                                <div class="text-center">

                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-50 text-sky-600">

                                        <i class="fa-solid fa-users text-xl"></i>

                                    </div>

                                    <h3 class="mt-4 text-sm font-semibold text-slate-800">
                                        Tidak ada user ditemukan
                                    </h3>

                                    <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">
                                        Belum terdapat data pengguna pada kategori
                                        yang sedang dipilih.
                                    </p>

                                    @if(!request('trashed'))

                                    <a
                                        href="{{ route('security.users.create') }}"
                                        class="mt-5 inline-flex items-center gap-2 rounded-xl bg-sky-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-sky-800 hover:shadow-md">

                                        <i class="fa-solid fa-plus text-xs"></i>

                                        Tambah User

                                    </a>

                                    @endif

                                </div>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =========================================================
            PAGINATION
        ========================================================== --}}
        @if($users->hasPages())

        <div class="mt-6">
            {{ $users->links() }}
        </div>

        @endif

    </div>

</div>

@endsection