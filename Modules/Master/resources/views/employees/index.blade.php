@extends('shared::layouts.app')

@section('title', 'Manajemen Karyawan')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    @if(session('success'))
    <div class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        <i class="fa-solid fa-circle-check mt-0.5"></i>
        <span>
            {{ session('success') }}
        </span>
    </div>
    @endif

    <div class="mb-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('master.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-sky-900 hover:bg-sky-950 transition duration-200 translate-x-0 hover:-translate-x-1">
                    <i class="fa fa-arrow-left text-md text-sky-200"></i>
                </a>
                <h1 class="text-lg font-bold text-sky-800">Manajemen Karyawan</h1>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('master.employees.create') }}">
                    <x-shared::button variant="primary" icon="fa-solid fa-plus">
                        <span class="text-sky-200">Tambah Department</span>
                    </x-shared::button>
                </a>
            </div>
        </div>
    </div>

    {{-- =========================================================
            STATUS TABS
        ========================================================== --}}
    <div class="mb-6">

        <div class="inline-flex rounded-xl border border-slate-200 bg-white p-1 shadow-sm">

            {{-- Aktif --}}
            <a
                href="{{ route('master.employees.index') }}"
                class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-all duration-200
                    {{ !request('trashed')
                        ? 'bg-sky-900 text-white shadow-sm'
                        : 'text-slate-500 hover:bg-sky-50 hover:text-sky-800'
                    }}">

                <span class="h-2 w-2 rounded-full
                        {{ !request('trashed') ? 'bg-emerald-300' : 'bg-emerald-500' }}">
                </span>

                Aktif

            </a>


            {{-- Nonaktif --}}
            <a
                href="{{ route('master.employees.index', ['trashed' => 1]) }}"
                class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-all duration-200
                    {{ request('trashed')
                        ? 'bg-sky-900 text-white shadow-sm'
                        : 'text-slate-500 hover:bg-sky-50 hover:text-sky-800'
                    }}">

                <span class="h-2 w-2 rounded-full
                        {{ request('trashed') ? 'bg-red-300' : 'bg-red-500' }}">
                </span>

                Nonaktif

            </a>

        </div>

    </div>


    {{-- =========================================================
            EMPLOYEE TABLE
        ========================================================== --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Table Header --}}
        <div class="hidden border-b border-slate-200 bg-sky-100 px-6 py-4 lg:grid lg:grid-cols-12 lg:gap-4">

            <div class="col-span-2 text-xs font-semibold uppercase tracking-wide text-sky-950">
                Karyawan
            </div>

            <div class="col-span-2 text-xs font-semibold uppercase tracking-wide text-sky-950">
                Email
            </div>

            <div class="col-span-2 text-xs font-semibold uppercase tracking-wide text-sky-950">
                Status Kepegawaian
            </div>

            <div class="col-span-2 text-xs font-semibold uppercase tracking-wide text-sky-950">
                Status
            </div>

            <div class="col-span-4 text-center text-xs font-semibold uppercase tracking-wide text-sky-950">
                Aksi
            </div>

        </div>


        {{-- Table Body --}}
        <div class="divide-y divide-slate-100">

            @forelse($employees as $employee)

            <div class="group px-4 py-5 transition-colors duration-200 hover:bg-sky-50/40 sm:px-6">

                <div class="grid grid-cols-1 gap-5 lg:grid-cols-12 lg:items-center lg:gap-4">


                    {{-- Employee --}}
                    <div class="lg:col-span-2">

                        <div class="flex items-center gap-3">

                            <x-shared::avatar
                                :src="$employee->photo ? asset('storage/' . $employee->photo) : null"
                                :name="$employee->name"
                                size="sm" />

                            <div class="min-w-0">

                                <p class="truncate text-sm font-semibold text-slate-800">
                                    {{ $employee->name }}
                                </p>

                                <p class="mt-0.5 text-xs text-slate-400">
                                    Karyawan
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Email --}}
                    <div class="lg:col-span-2">

                        <p class="text-sm text-slate-600 break-all">
                            {{ $employee->user->email ?? '-' }}
                        </p>

                    </div>


                    {{-- Employment Status --}}
                    <div class="lg:col-span-2">

                        <span class="text-sm text-slate-600">
                            {{ $employee->employmentStatus->name ?? '-' }}
                        </span>

                    </div>


                    {{-- Status --}}
                    <div class="lg:col-span-2">

                        @if($employee->trashed())

                        <x-shared::badge
                            variant="danger"
                            dot>
                            Nonaktif
                        </x-shared::badge>

                        @else

                        <x-shared::badge
                            variant="success"
                            dot>
                            Aktif
                        </x-shared::badge>

                        @endif

                    </div>


                    {{-- Actions --}}
                    <div class="lg:col-span-4">

                        <div class="flex flex-wrap items-center justify-start gap-2 lg:justify-center">

                            @if($employee->trashed())

                            {{-- Restore --}}
                            <form
                                method="POST"
                                action="{{ route('master.employees.restore', $employee->slug) }}">
                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-2 text-xs font-medium text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-emerald-700 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">
                                    <i class="fa-solid fa-rotate-left"></i>

                                    <span>
                                        Aktifkan
                                    </span>
                                </button>

                            </form>


                            {{-- Permanent Delete --}}
                            <form
                                method="POST"
                                action="{{ route('master.employees.forceDelete', $employee->slug) }}"
                                onsubmit="return confirm('Hapus permanen? Tindakan ini tidak bisa dibatalkan.')">
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-red-600 px-3 py-2 text-xs font-medium text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-red-700 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                                    <i class="fa-solid fa-trash-can"></i>

                                    <span>
                                        Hapus Permanen
                                    </span>
                                </button>

                            </form>

                            @else

                            {{-- Detail --}}
                            <a
                                href="{{ route('master.employees.show', $employee->slug) }}"
                                class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-sky-900 px-3 py-2 text-xs font-medium text-white shadow-sm shadow-sky-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:bg-sky-800 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700">
                                <i class="fa-solid fa-eye"></i>

                                <span>
                                    Detail
                                </span>
                            </a>


                            {{-- Deactivate --}}
                            <form
                                method="POST"
                                action="{{ route('master.employees.destroy', $employee->slug) }}"
                                onsubmit="return confirm('Nonaktifkan karyawan ini?')">
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-red-600 px-3 py-2 text-xs font-medium text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-red-700 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                                    <i class="fa-solid fa-user-slash"></i>

                                    <span>
                                        Nonaktif
                                    </span>
                                </button>

                            </form>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

            @empty

            {{-- Empty State --}}
            <div class="px-6 py-16 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-50 text-sky-600">

                    <i class="fa-solid fa-users text-xl"></i>

                </div>

                <h3 class="mt-4 text-sm font-semibold text-slate-800">
                    Belum ada data karyawan
                </h3>

                <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">
                    Belum terdapat data karyawan pada kategori yang sedang dipilih.
                </p>

                @if(!request('trashed'))

                <a
                    href="{{ route('master.employees.create') }}"
                    class="mt-5 inline-flex items-center gap-2 rounded-xl bg-sky-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-sky-800 hover:shadow-md">
                    <i class="fa-solid fa-plus text-xs"></i>

                    Tambah Karyawan
                </a>

                @endif

            </div>

            @endforelse

        </div>

    </div>


    {{-- =========================================================
            PAGINATION
        ========================================================== --}}
    @if($employees->hasPages())

    <div class="mt-6">
        {{ $employees->links() }}
    </div>

    @endif

</div>
@endsection