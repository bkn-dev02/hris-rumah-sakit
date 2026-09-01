@extends('shared::layouts.app')

@section('title', 'Jenis Cuti')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between rounded-xl bg-[#edf5ee] px-5 py-4 shadow-sm">

        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[#173f34] shadow-sm">
                <i class="fa-solid fa-calendar-days"></i>
            </div>

            <div>
                <h1 class="text-xl font-bold text-[#173f34]">
                    Jenis Cuti
                </h1>
                <p class="mt-0.5 text-xs text-slate-500">
                    Kelola jenis cuti yang tersedia untuk karyawan
                </p>
            </div>
        </div>

        <a
            href="{{ route('leave.leave-types.create') }}"
            class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-[#173f34] via-[#1f4d3d] to-[#2a684f] px-4 py-2.5 text-sm font-medium text-white shadow-md transition duration-200 hover:-translate-y-0.5 hover:shadow-lg">

            <i class="fa-solid fa-plus text-xs"></i>
            Tambah Jenis Cuti

        </a>

    </div>


    {{-- Success Alert --}}
    @if (session('success'))
    <div class="mb-4 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm">

        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100">
            <i class="fa-solid fa-check text-emerald-600"></i>
        </div>

        <span>
            {{ session('success') }}
        </span>

    </div>
    @endif


    {{-- Error Alert --}}
    @if (session('error'))
    <div class="mb-4 flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 shadow-sm">

        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-rose-100">
            <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
        </div>

        <span>
            {{ session('error') }}
        </span>

    </div>
    @endif


    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-[#dfeee1] bg-[#edf5ee] p-4 shadow-md">

        {{-- Table Header --}}
        <div class="hidden lg:grid lg:grid-cols-12 lg:items-center rounded-xl bg-gradient-to-r from-[#173f34] to-[#2a684f] px-5 py-4 shadow-md">

            <div class="col-span-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-hashtag"></i>
                <span>Kode</span>
            </div>

            <div class="col-span-3 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-tag"></i>
                <span>Nama</span>
            </div>

            <div class="col-span-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Kuota?</span>
            </div>

            <div class="col-span-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-circle-check"></i>
                <span>Status</span>
            </div>

            <div class="col-span-3 flex items-center justify-end gap-2 text-xs font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-gear"></i>
                <span>Aksi</span>
            </div>

        </div>


        {{-- Table Body --}}
        <div class="mt-3 space-y-3">

            @forelse ($leaveTypes as $leaveType)

            <div class="rounded-xl border border-[#dfeee1] bg-white p-4 shadow-sm transition duration-200 hover:border-[#dfeee1] hover:shadow-md">

                <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 lg:items-center">

                    {{-- Kode --}}
                    <div class="lg:col-span-2">
                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-400 lg:hidden">
                            Kode
                        </p>

                        <span class="inline-flex items-center rounded-lg bg-[#edf5ee] px-3 py-1.5 text-sm font-semibold text-[#173f34]">
                            {{ $leaveType->code }}
                        </span>
                    </div>


                    {{-- Nama --}}
                    <div class="lg:col-span-3">
                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-400 lg:hidden">
                            Nama
                        </p>

                        <p class="font-semibold text-[#173f34]">
                            {{ $leaveType->name }}
                        </p>
                    </div>


                    {{-- Kuota --}}
                    <div class="lg:col-span-2">
                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-400 lg:hidden">
                            Kuota
                        </p>

                        @if ($leaveType->requires_quota)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-[#edf5ee] px-3 py-1 text-xs font-semibold text-[#1f4d3d]">

                            <span class="h-1.5 w-1.5 rounded-full bg-[#2a684f]"></span>

                            Ya
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">

                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>

                            Tidak
                        </span>
                        @endif
                    </div>


                    {{-- Status --}}
                    <div class="lg:col-span-2">
                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-400 lg:hidden">
                            Status
                        </p>

                        @if ($leaveType->is_active)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">

                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                            Aktif
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">

                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>

                            Nonaktif
                        </span>
                        @endif
                    </div>


                    {{-- Aksi --}}
                    <div class="flex items-center justify-start gap-2 lg:col-span-3 lg:justify-end">

                        <a
                            href="{{ route('leave.leave-types.edit', $leaveType) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-[#edf5ee] px-3 py-2 text-xs font-semibold text-[#1f4d3d] transition duration-200 hover:bg-[#edf5ee] hover:-translate-y-0.5">

                            <i class="fa-solid fa-pen text-[11px]"></i>
                            Edit

                        </a>

                        <form
                            action="{{ route('leave.leave-types.destroy', $leaveType) }}"
                            method="POST"
                            class="inline"
                            onsubmit="return confirm('Hapus jenis cuti ini?');">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-600 transition duration-200 hover:bg-rose-100 hover:-translate-y-0.5">

                                <i class="fa-solid fa-trash text-[11px]"></i>
                                Hapus

                            </button>

                        </form>

                    </div>

                </div>

            </div>

            @empty

            <div class="rounded-xl border border-[#dfeee1] bg-white p-10 text-center shadow-sm">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#edf5ee] text-[#1f4d3d]">
                    <i class="fa-solid fa-calendar-xmark text-xl"></i>
                </div>

                <p class="mt-4 text-sm font-medium text-slate-600">
                    Belum ada jenis cuti.
                </p>

                <p class="mt-1 text-xs text-slate-400">
                    Silakan tambahkan jenis cuti terlebih dahulu.
                </p>

            </div>

            @endforelse

        </div>

    </div>


    {{-- Pagination --}}
    <div class="mt-4">
        {{ $leaveTypes->links() }}
    </div>

</div>
@endsection
